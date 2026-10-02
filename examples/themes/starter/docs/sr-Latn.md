## Kako izgleda

Topla stranica nalik papiru: krem pozadina, tamnobraon tekst, ćilibarski akcenat za linkove i dugmad i naslovi
serifnim pismom. Uglovi dugmadi i slika su oštriji nego u podrazumevanoj temi.

## Šta dodaje

- Blok baner za uređivač stranica: jedan red teksta na akcentnoj boji preko cele širine stranice, sa linkom
  posle njega.
- Red iznad naslova hero bloka koji ispisuje ime sajta.

## Za programere

Tema živi u `themes/starter`. Njena klasa `Theme` je opisuje; `public/theme.css` daje tokenima dizajna sajta druge
vrednosti i stilizuje baner; `blocks/hero/views/hero.php` zamenjuje view hero bloka sajta, na istoj putanji
kao u `apps/frontend/blocks`; `blocks/banner/` je njen sopstveni blok; `public/screenshots/` drži slike
prikazane ovde, `messages/` prevode, a `docs/` ovaj tekst. Kopirajte folder da započnete sopstvenu temu.
