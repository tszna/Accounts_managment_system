# User accounts managment system
Jest to API, napisane w laravelu, zawierające operacje CRUD na kontach użytkowników. API wykorzystuje bazę danych MySQL. Rozważałem różne wersję struktury bazy danych, ostatecznie zdecydowałem się na taką jak ta poniżej rozrysowana przy pomocy aplikacji diagrams.

<img src="https://i.imgur.com/S8GNxtX.png" alt="diagram">

Fundamentem bazy danych jest to, że są dwa rodzaje użytkowników: wykładowca i pracownik administracyjny. Użytkownik może być jednocześnie wykładowcą i pracownikiem administracyjnym, dlatego baza danych zawiera tabele łączące. Użytkownik, oprócz danych własnych, ma również różne typy danych w zależności od tego jakiego typu jest użytkownikiem.<br>
API ma również implementację validacji, która np. nie pozwala dodać użytkownika, który ma taki sam numer telefonu czy mail, jak inny użytkownik w bazie danych. Validacja obsługuje również wyrażenia reguarne, które nie pozwalają dodać hasła NIE zawierającego przynajmniej jednej dużej litery, jednej małej listery, jednej cyfry i jednego znaku specjalnego oraz maila niezgodnego z wzorcem.<br>
Każda akcja CRUD jest rejestrowana w pliku laravel.log, a akcja update ma dodatkowo zaimplementowaną funkcjonalność, dzięki której w pliku logu wskazywane są pola, które zostały zmienione.

<h4>Instalacja projektu</h4>
Po pobraniu projektu należy w katalogu laravela wpisać w terminalu komendę:
<pre><code>composer install</code></pre>
następnie:
<pre><code>cp .env.example .env</code></pre>
Teraz należy zmienić w pliku .env dane dostepu do bazy danych, później np. w programie xampp uruchomić obsługę MySQL i dodać użytkownika w panelu zarządzania SQL.
W kolejnym kroku można wpisać w terminalu komendę:
<pre><code>php artisan migrate:fresh --seed</code></pre>
jeśli chcemy zapełnić bazę danych przypadkowymi danymi.
A na koniec, aby uruchomić server, należy wpisać:
<pre><code>php artisan serve</code></pre>
Dla akcji create, została zbudowana dokumentacja w ramach swagger ui, można ją znaleść pod adresem:
<pre><code>localhost:8000/api</code></pre>
a prezentuje się w ten sposób:<br> 
<p></p>
<img src="https://i.imgur.com/cokTzI9.png" alt="swagger">