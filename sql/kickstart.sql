/*
================================================================================
網路書店 - Database Setup Script
================================================================================
DATABASE:      OnlineBookstoreDB
DBMS:          Microsoft SQL Server
DESCRIPTION:   Creates the database and schema, and inserts sample data for
               the project. Includes Taiwanese Mandarin stroke collation support.
================================================================================
*/

USE master;
GO

IF DB_ID('OnlineBookstoreDB') IS NOT NULL
BEGIN
    ALTER DATABASE OnlineBookstoreDB SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE OnlineBookstoreDB;
END
GO

-- ================================================================================
-- DATABASE CREATION
-- ================================================================================

CREATE DATABASE OnlineBookstoreDB
COLLATE Chinese_Taiwan_Stroke_CI_AS;
GO

USE OnlineBookstoreDB;
GO

-- ================================================================================
-- TABLE DROPPING
-- ================================================================================

IF OBJECT_ID('dbo.order_items', 'U') IS NOT NULL
    DROP TABLE dbo.order_items;
GO
IF OBJECT_ID('dbo.orders', 'U') IS NOT NULL
    DROP TABLE dbo.orders;
GO
IF OBJECT_ID('dbo.books', 'U') IS NOT NULL
    DROP TABLE dbo.books;
GO
IF OBJECT_ID('dbo.users', 'U') IS NOT NULL
    DROP TABLE dbo.users;
GO


-- ================================================================================
-- TABLE CREATION
-- ================================================================================

-- Table for user accounts
CREATE TABLE dbo.users (
    user_id     INT IDENTITY(1,1) PRIMARY KEY,
    username    NVARCHAR(50) NOT NULL UNIQUE,
    password    NVARCHAR(255) NOT NULL,
    email       NVARCHAR(100) NOT NULL UNIQUE,
    name        NVARCHAR(100) NOT NULL,
    is_admin    BIT NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT GETDATE()
);
GO

-- Table for book inventory
CREATE TABLE dbo.books (
    book_id         INT IDENTITY(1,1) PRIMARY KEY,
    title           NVARCHAR(255) NOT NULL,
    author          NVARCHAR(100) NOT NULL,
    isbn            NVARCHAR(20) NULL,
    price           DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
    stock           INT NOT NULL CHECK (stock >= 0),
    description     NVARCHAR(MAX) NULL,
    created_at      DATETIME NOT NULL DEFAULT GETDATE()
);
GO

-- Table for customer orders
CREATE TABLE dbo.orders (
    order_id        INT IDENTITY(1,1) PRIMARY KEY,
    user_id         INT NOT NULL,
    order_date      DATETIME NOT NULL DEFAULT GETDATE(),
    total_amount    DECIMAL(10, 2) NOT NULL,
    status          NVARCHAR(50) NOT NULL DEFAULT 'Pending' CHECK (status IN 
                ('Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled')),
    CONSTRAINT FK_orders_users FOREIGN KEY (user_id) REFERENCES users(user_id)
);
GO

-- Table for order details
CREATE TABLE dbo.order_items (
    order_item_id   INT IDENTITY(1,1) PRIMARY KEY,
    order_id        INT NOT NULL,
    book_id         INT NOT NULL,
    quantity        INT NOT NULL CHECK (quantity > 0),
    price_at_order  DECIMAL(10, 2) NOT NULL,
    CONSTRAINT FK_order_items_orders FOREIGN KEY (order_id) 
                REFERENCES orders(order_id) ON DELETE CASCADE,
    CONSTRAINT FK_order_items_books FOREIGN KEY (book_id) REFERENCES books(book_id)
);
GO

-- ================================================================================
-- SAMPLE DATA INSERTION
-- ================================================================================


-- Insert sample books
INSERT INTO dbo.books (title, author, isbn, price, stock, description) VALUES
('PHP 8 網頁設計聖經', '李大師', '978-986-123-456-7', 750.00, 50, '從入門到精通，涵蓋 PHP 8 最新特性與實戰技巧。'),
('精通 SQL Server 2022', '王強', '978-986-234-567-8', 980.00, 35, '深入探討 SQL Server 2022 的效能調校、高可用性與安全性。'),
('Docker 容器實戰', '陳雲', '978-986-345-678-9', 620.00, 120, '學習如何使用 Docker 快速建構、部署與管理應用程式。'),
('現代前端開發: HTML, CSS 與 JavaScript', '林芬', '978-986-456-789-0', 800.00, 80, '涵蓋最新的前端技術，打造互動式與響應式網頁。'),
('演算法圖鑑', '張程式', '978-986-567-890-1', 550.00, 200, NULL),
('專案管理的第一本書', '趙經理', '978-986-678-901-2', 450.00, 5, '給新手的專案管理指南，幫助你順利完成期末專案。');
GO

PRINT '================================================================================';
PRINT 'Database schema and sample data created successfully.';
PRINT '================================================================================';
GO