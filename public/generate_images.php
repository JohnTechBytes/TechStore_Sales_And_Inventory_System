<?php
// Generate placeholder images - run: php public/generate_images.php

$targetDir = __DIR__ . '/uploads/products/';
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

$products = [
    'tanduay_rhum.jpg' => 'Tanduay Rhum',
    'karbaw_energy.jpg' => 'Karbaw',
    'rooster_chicken.jpg' => 'Rooster',
    'monkey_snack.jpg' => 'Monkey',
    'rub_product.jpg' => 'RUB',
    'san_miguel_pale_pilsen.jpg' => 'San Miguel Pale Pilsen',
    'coke_1.5l.jpg' => 'Coke 1.5L',
    'piattos.jpg' => 'Piattos',
    'lucky_me_noodles.jpg' => 'Lucky Me Noodles',
    'downy.jpg' => 'Downy',
    'mountain_dew.jpg' => 'Mountain Dew',
    'clover_chips.jpg' => 'Clover Chips',
    'argentina_corned_beef.jpg' => 'Argentina Corned Beef',
    'safeguard_soap.jpg' => 'Safeguard Soap',
    'zonrox.jpg' => 'Zonrox Bleach',
    'red_horse_beer.jpg' => 'Red Horse Beer',
    'nova_crackers.jpg' => 'Nova Crackers',
    'century_tuna.jpg' => 'Century Tuna',
    'colgate.jpg' => 'Colgate',
    'tide.jpg' => 'Tide',
    'ginebra_san_miguel.jpg' => 'Ginebra San Miguel',
    'chippy.jpg' => 'Chippy',
    'purefoods_corned_beef.jpg' => 'Purefoods Corned Beef',
    'head_and_shoulders.jpg' => 'Head & Shoulders',
    'mr_muscle.jpg' => 'Mr. Muscle',
    'emperador_light.jpg' => 'Emperador Light',
    'oishi_prawn_crackers.jpg' => 'Oishi Prawn Crackers',
    'spam.jpg' => 'Spam',
    'pantene.jpg' => 'Pantene',
    'domex.jpg' => 'Domex',
    'fundador.jpg' => 'Fundador',
    'vcut.jpg' => 'V-Cut',
    'del_monte_pineapple.jpg' => 'Del Monte Pineapple',
    'rexona.jpg' => 'Rexona',
    'lysol.jpg' => 'Lysol',
    'alfonso_light.jpg' => 'Alfonso Light',
    'tortillos.jpg' => 'Tortillos',
    'bear_brand_milk.jpg' => 'Bear Brand Milk',
    'nivea.jpg' => 'Nivea',
    'baygon.jpg' => 'Baygon',
    'the_bar.jpg' => 'The Bar',
    'moby.jpg' => 'Moby',
    'nescafe_3in1.jpg' => 'Nescafe 3in1',
    'gillette_razor.jpg' => 'Gillette',
    'joy_dishwashing.jpg' => 'Joy',
    'fundador_light.jpg' => 'Fundador Light',
    'tostillas.jpg' => 'Tostillas',
    'maggi_magic_sarap.jpg' => 'Maggi Magic Sarap',
    'ponds_cream.jpg' => "Pond's Cream",
    'scotch_brite.jpg' => 'Scotch Brite'
];

foreach ($products as $filename => $name) {
    $img = imagecreate(200, 200);
    $bg = imagecolorallocate($img, 240, 248, 255);
    $textColor = imagecolorallocate($img, 0, 0, 0);
    imagefilledrectangle($img, 0, 0, 200, 200, $bg);
    
    // Center text manually (simplified)
    $lines = explode("\n", wordwrap($name, 18, "\n"));
    $y = 100 - ((count($lines) - 1) * 7);
    foreach ($lines as $line) {
        imagestring($img, 5, 10, $y, $line, $textColor);
        $y += 15;
    }
    
    imagejpeg($img, $targetDir . $filename, 80);
    // No imagedestroy() needed – PHP will clean up
    echo "Generated: $filename\n";
}
echo "✓ All images created in: $targetDir\n";