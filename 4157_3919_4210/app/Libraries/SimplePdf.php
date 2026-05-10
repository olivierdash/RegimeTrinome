<?php

namespace App\Libraries;

class SimplePdf
{
    public function purchaseSummary(array $user, array $purchase, array $sports): string
    {
        $lines = [
            'Regime alimentaire - fiche de suivi',
            '',
            'Utilisateur : ' . ($user['nom'] ?? ''),
            'Regime : ' . ($purchase['regime'] ?? ''),
            'Objectif : ' . ($purchase['objectif'] ?? ''),
            'Date debut : ' . ($purchase['date_debut'] ?? ''),
            'Duree : ' . ($purchase['duree_jours'] ?? '') . ' jour(s)',
            'Montant : ' . number_format((float) ($purchase['montant_total'] ?? 0), 0, ',', ' ') . ' Ar',
            '',
            'Variation prevue : ' . number_format((float) ($purchase['poids_par_jour'] ?? 0), 3, ',', ' ') . ' kg/jour',
            'Composition : viande ' . ($purchase['pourcentage_viande'] ?? 0) . '%, poisson ' . ($purchase['pourcentage_poisson'] ?? 0) . '%, volaille ' . ($purchase['pourcentage_volaille'] ?? 0) . '%',
            '',
            'Activites sportives conseillees :',
        ];

        if ($sports === []) {
            $lines[] = '- Aucune activite associee.';
        } else {
            foreach ($sports as $sport) {
                $lines[] = '- ' . $sport['designation'] . ' : ' . $sport['duree_minutes_jour'] . ' min/jour';
            }
        }

        return $this->document($lines);
    }

    private function document(array $lines): string
    {
        $content = "BT\n/F1 16 Tf\n50 790 Td\n";
        foreach ($this->wrappedLines($lines) as $index => $line) {
            if ($index === 1) {
                $content .= "/F1 11 Tf\n";
            }
            $content .= '(' . $this->escape($line) . ") Tj\n0 -18 Td\n";
        }
        $content .= "ET\n";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $number => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($number + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }

    private function wrappedLines(array $lines): array
    {
        $wrapped = [];
        foreach ($lines as $line) {
            $text = $this->ascii((string) $line);
            if ($text === '') {
                $wrapped[] = '';
                continue;
            }

            foreach (str_split($text, 86) as $part) {
                $wrapped[] = $part;
            }
        }

        return array_slice($wrapped, 0, 38);
    }

    private function ascii(string $text): string
    {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '', $text) : $converted;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
