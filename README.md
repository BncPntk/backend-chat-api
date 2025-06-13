# Chat API

## Telepítés

- PHP 8.3+
- MySQL
- Composer
- Laravel 12

```bash
git clone https://github.com/BncPntk/backend-chat-api.git
cd backend-chat-api
```

```bash
composer install

cp .env.example .env

php artisan key:generate
```

## Adatbázis kapcsolat
- DB_DATABASE=chat-api
- DB_USERNAME=root
- DB_PASSWORD=

## Migráció + seed

```bash
php artisan migrate --seed
```

## Felhasználó megerősítése Tinker-rel

Seeder vagy manuális regisztráció után a felhasználó emailben megerősítettnek jelölhető tinkerben is
```bash
php artisan tinker

$user = \App\Models\User::where('email', 'regisztralt@email.com')->first();
$user->markEmailAsVerified();
```

## Tesztelés

```bash
php artisan test
```
3 feature teszt van:
- Regisztráció email megerősítéssel
- Ismerős jelölés
- Üzenetküldés

"...tests/Unit" not found hiba esetén
```bash
mkdir tests/Unit

php artisan test
```
---

## API tesztelése Bruno-val

A projekt tartalmaz egy `bruno/` nevű mappát, amely importálható a [Bruno](https://www.usebruno.com/) API tesztelő kliensbe.

A mappa ezeket a végpontokat tartalmazza:

- **Register** – új felhasználó regisztrációja
- **Login** – bejelentkezés, token lekérése
- **Add Friend** – ismerős hozzáadása
- **Index Friends** – saját barátlista lekérdezése
- **Index Users** – elérhető felhasználók listázása (lapozva/szűrve)
- **Send Msg** – szöveges üzenet küldése egy ismerősnek
- **Show Msg** – bejövő és kimenő üzenetek listázása

Megerősítés majd sikeres belépés után a kapott tokent meg kell adni a hívásoknak, <br>**Headers** szekcióban Authorization: Bearer {token} formában, vagy a<br>**Auth** Bearer Token mezőben
