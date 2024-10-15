# StudyCaf

## Project Overview

**StudyCaf** is a study café management system designed to provide users with an efficient way to interact with a study café environment. This system offers functionalities for managing user roles, making reservations, handling products, and tracking loyalty points. The database structure is implemented using MySQL 8.0 and serves as the backbone for the operations of the application.

- **Created:** 04/09/2024
- **Modified:** 15/10/2024
- **Model:** Studycaf
- **Database:** MySQL 8.0

## Features

- User management, including registration, authentication, and role assignments.
- Product management: available products and student price.
- Table reservations for study sessions.
- Loyalty point tracking system for users.

## Database Schema

The database model includes several key tables that are fundamental to the system's operations. Below is an overview of the tables and their relationships:

### Tables

- **citys**: Stores information about cities, including name and postal number.
- **users**: Manages user data such as email, password, address, and relationships to `citys`, `genders`, and `pictures` (for certificates).
- **roles**: Defines roles such as admin, user, student, etc.
- **user_role**: Connects users to specific roles.
- **products**: Contains product data, including price, student price, and related pictures.
- **categories**: Represents different product categories.
- **tables**: Stores information on available tables in the café.
- **pictures**: Stores image files for various entities, such as product pictures and certificates.
- **reservations**: Manages reservations made by users, including time and table data.
- **points**: Tracks loyalty points assigned to users.
- **coupons**: Contains discount coupons available for users, tied to specific roles.
- **product_coupon**: Associates products with available discount coupons.
- **genders**: Stores possible genders for users.
- **password_resets**: Stores information for password reset requests.

### Relationships

- **users** are connected to **citys**, **genders**, and **pictures**.
- **user_role** connects **users** to **roles** to assign privileges.
- **products** have relationships with **categories** and **pictures**.
- **reservations** involve **users** and **tables** to manage bookings.
- **points** are assigned to **users** to maintain a loyalty program.

## Installation

To run this project locally, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/TamseSaso/StudyCaf.git
   ```

2. Set up your MySQL database using the provided SQL script (`studycaf.sql`). Make sure to create the necessary tables and relationships as specified.

3. Configure your environment variables in the `.env` file (e.g., database credentials).

4. Start the web server:
   ```bash
   php -S localhost:8000
   ```

## Usage

1. **User Registration/Login**: Users can register and log in to make reservations and access features such as loyalty points.
2. **Make a Reservation**: Book a table at the café for a study session.
3. **View Products**: Browse available products, including beverages, food items, and more.
5. **Track Points**: Earn and redeem loyalty points.

## Technologies Used

- **Front-end**: HTML, CSS (Tailwind CSS), JavaScript
- **Back-end**: PHP
- **Database**: MySQL 8.0

## Future Improvements

- Add support for online payments for reservations and products.
- Implement an interactive dashboard for café managers to view and manage reservations.
- Add more customization options for loyalty rewards and coupon generation.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Contact

For any questions or suggestions, please feel free to reach out to us at [saso.tamse@scv.si](mailto:saso.tamse@scv.si).
