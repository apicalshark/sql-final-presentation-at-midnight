# 2025 DB Final Presentation


> my DB final presentation repo


---

## Table of Contents

- [About the Project](#about-the-project)
  - [Built With](#built-with)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [License](#license)
- [Acknowledgments](#acknowledgments)

---

## About The Project

This project is a simple web application. It was built to demonstrate the use of mssql and php together with both front-end and backend in a simple set-up.

### Built With

*   php8.4
*   SQL Server 2022
*   Apache
*   Tailwind CSS
*   Docker Compose

---



## Getting Started

To get website up and running, follow these steps:


### Prerequisites

Any Operating System with Docker Compose


### Installation


1.  **Clone repository:**

    ```bash
    git clone https://github.com/apicalshark/sql-final-presentation-at-midnight/
    cd sql-final-presentation-at-midnight
    ```


2.  **Build Image and Run:**

    ```bash
    docker compose up -d --build
    ```

    To shutdown the container, run:

    ```bash
    docker compose down
    ```


---

## Usage

When all container is up and running, open your web browser and go to `http://localhost`.



### License

Distributed under the Apache Version 2.0 License. See `LICENSE` for more information.


### Acknowledgments

- [shinsenter](https://github.com/shinsenter/php) PHP Image

- [Namoshek](https://github.com/Namoshek/docker-php-mssql) SQL Server Driver