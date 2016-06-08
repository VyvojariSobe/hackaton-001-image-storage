<?php
use Nette\Utils\Image;

return [
    'storageDir' => __DIR__ . '/../storage',
    'useNoImage' => true,
    'noImageUrl' => 'https://atoto.cz/images/no-product.png',
    'profiles'   => [
        'detail'     => [
            ['resize', 300, 300, Image::SHRINK_ONLY],
        ],
        'list'       => [
            ['resize', 150, 150, Image::SHRINK_ONLY],
        ],
        'large'      => [
            ['resize', 1000, 1000, Image::SHRINK_ONLY],
        ],
        'bw-640x360' => [
            ['resize', 320, 180, Image::SHRINK_ONLY],
            ['filter', IMG_FILTER_GRAYSCALE],
        ],
    ],
    'excludedImages' => [
        '57d1df792497be6fdcab3906ee41ce43',
        'c0042d7f298a9cecd5bfbc85455bb4fc',
        '724d325efc858a1f1dc3cb1a42544fa6',
        '7766628289758ae1722b496aa1445391',
        '4291c96cc3140a6a80d102467dd36adb',
        '4b44147ac051b49ef35b7d1ba5837eaa',
        '5a815da010d8e5bc71c8163c571fd8b3',
        '15585257a3ba84af91dd60daafd47c4c',
        'ec5352928082226ad194b17da015491f',
        '9ee67ee78380b89f58617d286a551d38',
        '2c749be4247babbde84912aa9711bbe9',
        '1674a7fa029388d19d9d3aaaaec42b28',
    ],
];
