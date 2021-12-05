# User accounts managment system
Jest to API, napisane w laravelu, zawierające operacje CRUD na kontach użytkowników. API wykorzystuje bazę danych MySQL. Rozważałem różne wersję struktury bazy danych, ostatecznie zdecydowałem się na taką jak ta poniżej rozrysowana przy pomocy aplikacji diagrams.

<img src="https://i.imgur.com/S8GNxtX.png" alt="diagram">

Fundamentem bazy danych jest to, że są dwa rodzaje użytkowników: wykładowca i pracownik administracyjny. Użytkownik może być jednocześnie wykładowcą i pracownikiem administracyjnym, dlatego baza danych zawiera tabele łączące. Użytkownik, oprócz danych własnych, ma również różne typy danych w zależności od tego jakiego typu jest użytkownikiem.<br>
API ma również implementację validacji, która np. nie pozwala dodać użytkownika, który ma taki sam numer telefonu czy mail, jak inny użytkownik w bazie danych. Validacja obsługuje również wyrażenia reguarne, które nie pozwalają dodać hasła NIE zawierającego przynajmniej jednej dużej litery, jednej małej listery, jednej cyfry i jednego znaku specjalnego oraz maila niezgodnego z wzorcem.<br>
Każda akcja CRUD jest rejestrowana w pliku laravel.log, a akcja update ma dodatkowo zaimplementowaną funkcjonalność, dzięki której w pliku logu wskazywane są pola, które zostały zmienione.<br> 
Zaimplementowałem również endpoint logowania przy użyciu własnego systemu tokenów, które podczas weryfikacji w trakcie logowania, podlegają potrójnej walidacji. System nie przepuszcza tokenów, które zostały w jakikolwiek sposób zmodyfikowane oraz sprawdza czy przeglądarka jest taka sama jak ta, która została użyta podczas logowania. Takie systemy bezpieczeństwa wzmacniają poziom bezpieczeństwa na wypadek gdyby ktoś ukradł token.

<h4>Instalacja projektu</h4>
Po pobraniu projektu należy w katalogu laravela wpisać w terminalu komendę:
<pre><code>composer install</code></pre>
Następnie w bazie mysql utworzyć bazę danych o nazwie: shop, i użytkownika z uprawnieniami do tej bazy danych o loginie: shop i haśle: shop.
W kolejnym kroku należy zaimportować do stworzonej bazy danych plik sql, który znajduje się w głównym katalogu aplikacji.
A na koniec, aby uruchomić server, należy wpisać:
<pre><code>php artisan serve</code></pre>
Dla akcji create, została zbudowana dokumentacja w ramach swagger ui, można ją znaleźć pod adresem:
<pre><code>localhost:8000/api</code></pre>
a prezentuje się w ten sposób:<br> 
<p></p>
<img src="https://i.imgur.com/cokTzI9.png" alt="swagger">

<h4>Uruchomienia projektu poprzez docker</h4>
Po pobraniu projektu należy skopiować zawartość katalogu Docker i wkleić do głównego katalogu aplikacji, w taki sposób aby nadpisać istniejące pliki. Następnie należy uruchomić terminal w głównym katalogu aplikacji i wpisać komendę:
<pre><code>docker-compose up</code></pre>
W kolejnym kroku należy uruchmić phpmyadmin i zaimportować do istniejącej bazy danych plik sql, który znajduje się w głównym katalogu aplikacji.
Kontener phpmyadmin nasłuchuje domyślnie na porcie 8080, a php na porcie 80. Domyślne porty można zmienić w pliku docker-compose.yml
