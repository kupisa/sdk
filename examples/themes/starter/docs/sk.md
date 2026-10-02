## Ako vyzerá

Teplá stránka pripomínajúca papier: krémové pozadie, tmavohnedý text, jantárový akcent pre odkazy a tlačidlá a
nadpisy pätkovým písmom. Rohy tlačidiel a obrázkov sú ostrejšie než v predvolenej téme.

## Čo pridáva

- Blok banner pre editor stránok: jeden riadok textu na akcentovej farbe cez celú šírku stránky, s odkazom za
  ním.
- Riadok nad nadpisom hero bloku s názvom stránky.

## Pre vývojárov

Téma žije v `themes/starter`. Jej trieda `Theme` ju opisuje; `public/theme.css` dáva tokenom dizajnu stránky iné
hodnoty a štýluje banner; `blocks/hero/views/hero.php` nahrádza view hero bloku stránky na rovnakej ceste
ako v `apps/frontend/blocks`; `blocks/banner/` je jej vlastný blok; `public/screenshots/` obsahuje obrázky
zobrazené tu, `messages/` preklady a `docs/` tento text. Skopírujte priečinok a začnite vlastnú tému.
