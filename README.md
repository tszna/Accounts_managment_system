# User accounts managment system
Jest to API zawierające operacje CRUD na kontach użytkowników. API wykorzystuje bazę danych MySQL. Rozważałem różne wersję struktury bazy danych, ostatecznie zdecydowałem się na taką jak ta poniżej rozrysowana przy pomocy aplikacji diagrams.

<img src="https://i.imgur.com/S8GNxtX.png" alt="diagram">

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
API nie jest jeszcze ukończone. Na ten moment zawiera metody read, delete oraz prawie skończoną metodę create. W najbliższym czasie API będzie uzupełnione o metodę create wraz z validacją danych zapisywanych do bazy danych, metodę update oraz plik logu, do którego będą rejestrowane operacje CRUD.