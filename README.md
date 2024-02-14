## Installation

To get started with this project, follow these steps:

1. **Clone the repository**:

   ```bash
   git clone https://github.com/YonesSohrabi/shahab.git
   ```

2. **Navigate to the project directory**:

   ```bash
   cd shahab
   ```

3. **Install dependencies**:

   ```bash
   composer install
   ```

4. **Set up your environment file**:

   ```bash
   cp .env.example .env
   ```

   Make any necessary changes to the `.env` file for your environment.

5. **Generate an application key**:

   ```bash
   php artisan key:generate
   ```

6. **Run database migrations**:

   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**:

   If your project includes seeders, you can run them to populate the database with sample data.

   ```bash
   php artisan db:seed
   ```

8. **Start the development server**:

   ```bash
   php artisan serve
   ```

   You can now access your project at [http://localhost:8000](http://localhost:8000).

9. **(Optional) Configure Docker (if applicable)**:

   If you prefer to use Docker for local development, follow the instructions in the Docker section below.

## Usage

Once the project is set up and running, you can start using it. Here are some common tasks:

- **Accessing the application**: Open your web browser and navigate to [http://localhost:8000](http://localhost:8000) to access the application.
- **Logging in (if applicable)**: If your application requires authentication, you may need to register for an account or use default login credentials.
- **Exploring features**: Take some time to explore the features of the application and familiarize yourself with its functionality.

## Docker

If you prefer to use Docker for local development, follow these steps:

1. **Build the Docker containers**:

   ```bash
   docker-compose build
   ```

2. **Start the Docker containers**:

   ```bash
   docker-compose up -d
   ```

3. **Access the application**:

   Once the containers are up and running, you can access your application at [http://localhost:8080](http://localhost:8080).
