# System Alertów Bezpieczeństwa

## Opis projektu
System Alertów Bezpieczeństwa to aplikacja webowa oparta na frameworku Symfony, która umożliwia zarządzanie alertami bezpieczeństwa i zgłoszeniami użytkowników. Aplikacja pozwala na tworzenie, przeglądanie i zarządzanie alertami o zagrożeniach, które są wyświetlane na mapie. Użytkownicy mogą również zgłaszać potencjalne zagrożenia, które następnie są weryfikowane przez administratorów.

## Spis treści
1. [Opis projektu](#opis-projektu)
2. [Funkcjonalności](#funkcjonalności)
3. [Wymagania techniczne](#wymagania-techniczne)
4. [Instalacja](#instalacja)
5. [Uruchomienie aplikacji](#uruchomienie-aplikacji)
6. [Diagram klas UML](#diagram-klas-uml)

## Funkcjonalności
1. **System użytkowników**
   - Rejestracja i logowanie użytkowników
   - Role użytkowników (użytkownik, administrator)
   - Identyfikacja użytkowników przez numer telefonu

2. **Alerty bezpieczeństwa**
   - Tworzenie alertów bezpieczeństwa przez administratorów
   - Określanie poziomu zagrożenia, typu i statusu alertu
   - Definiowanie lokalizacji alertu (współrzędne geograficzne) i promienia działania
   - Wyświetlanie aktywnych alertów na mapie

3. **Zgłoszenia użytkowników**
   - Możliwość zgłaszania potencjalnych zagrożeń przez użytkowników
   - Określanie lokalizacji, typu i opisu zgłoszenia
   - Przeglądanie własnych zgłoszeń

4. **Panel administracyjny**
   - Zarządzanie użytkownikami
   - Zarządzanie alertami bezpieczeństwa
   - Zarządzanie zgłoszeniami użytkowników

## Wymagania techniczne
- PHP 8.2 lub nowszy
- Baza danych (SQLite w wersji deweloperskiej)
- Composer
- Symfony 7.3
- Google Maps API (klucz API wymagany do wyświetlania map)

## Instalacja
1. Sklonuj repozytorium:
   ```
   git clone [adres_repozytorium]
   cd project
   ```

2. Zainstaluj zależności za pomocą Composer:
   ```
   composer install
   ```

3. Skonfiguruj zmienne środowiskowe w pliku `.env`:
   - Ustaw połączenie z bazą danych
   - Dodaj klucz Google Maps API: `GOOGLE_MAPS_API_KEY=twój_klucz_api`

4. Utwórz bazę danych i wykonaj migracje:
   ```
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

## Uruchomienie aplikacji
1. Uruchom lokalny serwer deweloperski:
   ```
   symfony server:start
   ```
   lub
   ```
   php bin/console server:run
   ```

2. Otwórz przeglądarkę i przejdź do adresu `http://localhost:8000`

## Diagram klas UML
Diagram klas UML znajduje się w pliku `diagram.graphml` w głównym katalogu projektu. Można go otworzyć za pomocą narzędzia [yEd Graph Editor](https://www.yworks.com/products/yed) lub innego kompatybilnego edytora diagramów.

Główne klasy w systemie:
- **User** - reprezentuje użytkownika systemu
- **EmergencyAlert** - reprezentuje alert bezpieczeństwa
- **UserReport** - reprezentuje zgłoszenie użytkownika

Relacje między klasami:
- Użytkownik może utworzyć wiele zgłoszeń (relacja jeden-do-wielu)
- Alert bezpieczeństwa jest tworzony przez użytkownika (administratora)
