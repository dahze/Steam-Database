# Steam Database

Steam Database is a web application designed to provide users with detailed information about Steam games. The application allows users to search for games, view their details, and manage a wishlist. It also provides price comparisons for games across various currencies.

## Features
- **User Authentication**: Users can register and log in to access their personalized dashboard.

  ![Register](https://github.com/dahze/Steam-Database/assets/169538762/642ea4c7-9849-4c32-989a-ee9a3ddeacd7)
  ![Login](https://github.com/dahze/Steam-Database/assets/169538762/0e995da6-c073-4b8e-b0d2-768b3ee8ed75)
  ![Profile](https://github.com/dahze/Steam-Database/assets/169538762/b016583e-5d26-457c-8d46-9957def1ac97)

- **Search Functionality**: Users can search for games by name.

  ![GPSearch](https://github.com/dahze/Steam-Database/assets/169538762/ab0c0b63-3cf6-4c84-a826-eb695b0c71d1)

- **Recently Viewed Games**: Users can see a list of games they have recently viewed.

  ![Recent](https://github.com/dahze/Steam-Database/assets/169538762/fe0b8fe8-dfee-4c2b-964c-1fd78e8478c8)

- **Wishlist Management**: Users can add or remove games from their wishlist.

  ![Wishlist](https://github.com/dahze/Steam-Database/assets/169538762/210a6fad-498f-4f40-9913-bd88220df3d3)

- **Game Details**: Detailed information about each game, including developer, publisher, scores, and more.

  ![GPRecent](https://github.com/dahze/Steam-Database/assets/169538762/468a34eb-02ce-49f4-bb2e-5212a2ad8534)

- **Price Comparison**: Prices of games in different currencies are fetched from the Steam API and displayed.

## File Structure
- `dashboard.php`: Main dashboard page displaying recently viewed games and search functionality.
- `prices.php`: Fetches and updates game prices in various currencies from the Steam Store API.
- `game_page.php`: Displays detailed information about a selected game and allows wishlist management.
- `profile.php`: User profile page.
- `wishlist.php`: Displays the user's wishlist.
- `login.php`: User login page.
- `register.php`: User registration page.
- `search.php`: Handles the search functionality as well as fetches and updates game details from the Steam Spy API.
- `import.php`: Imports the 'appid' and 'name' of the entire Steam catalogue from the Steam Web API.
- `steamdb.css`: Stylesheet for the application.

## Database Schema
The application uses a MySQL database with the following tables:

![ER](https://github.com/dahze/Steam-Database/assets/169538762/55af8388-cfa7-4f64-a82d-3bc1e26f17ad)

## Data Flow
The following flowchart illustrates how the program fetches, stores, and displays data from different Steam APIs:

![API](https://github.com/dahze/Steam-Database/assets/169538762/3f7a273d-ce4f-42bc-a3fd-1e93a6c26746)

## Usage
1. **Register**: Access the registration page (`register.php`) to create a new account.
2. **Login**: Access the login page (`login.php`) to log in with your credentials.
3. **Dashboard**: Once logged in, you will be redirected to the dashboard (`dashboard.php`) where you can search for games and see recently viewed games.
4. **Search for Games**: Use the search bar on the dashboard or the search page (`search.php`) to find specific games.
5. **View Game Details**: Click on a game to view detailed information and price comparisons.
6. **Manage Wishlist**: Add or remove games from your wishlist directly from the game details page.
7. **Profile and Wishlist**: Access your profile (`profile.php`) and wishlist (`wishlist.php`) from the navigation bar.
