-- Create Database
CREATE DATABASE IF NOT EXISTS hostel_management;

USE hostel_management;

-- 1. User Table
CREATE TABLE User (
    User_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100) UNIQUE,
    Password VARCHAR(255),
    Role ENUM('Student', 'Employee') 
);

-- 2. Student Table
CREATE TABLE Student (
    Student_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Age INT,
    Gender VARCHAR(10),
    Contact VARCHAR(15),
    Address TEXT,
    User_ID INT,
    FOREIGN KEY (User_ID) REFERENCES User(User_ID)
);

-- 3. Employee Table
CREATE TABLE Employee (
    Employee_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Salary DECIMAL(10,2),
    Address TEXT,
    Role VARCHAR(50),
    User_ID INT,
    FOREIGN KEY (User_ID) REFERENCES User(User_ID)
);

-- 4. Hostel Table
CREATE TABLE Hostel (
    Hostel_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Address TEXT,
    No_of_Rooms INT,
    No_of_Students INT
);

-- 5. Room Table
CREATE TABLE Room (
    Room_ID INT AUTO_INCREMENT PRIMARY KEY,
    Room_Type VARCHAR(50),
    Capacity INT,
    Allocated BOOLEAN,
    Hostel_ID INT,
    FOREIGN KEY (Hostel_ID) REFERENCES Hostel(Hostel_ID)
);

-- 6. Allocation Table
CREATE TABLE Allocation (
    Allocation_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT,
    Room_ID INT,
    Allocation_Date DATE,
    Status VARCHAR(50),
    FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID),
    FOREIGN KEY (Room_ID) REFERENCES Room(Room_ID)
);

-- 7. Fee Table
CREATE TABLE Fee (
    Fee_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT,
    Amount DECIMAL(10,2),
    Due_Date DATE,
    Status VARCHAR(50),
    FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID)
);

-- 8. Food_Charge Table
CREATE TABLE Food_Charge (
    Food_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT,
    Month VARCHAR(20),
    Amount_Paid DECIMAL(10,2),
    Status VARCHAR(50),
    FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID)
);

-- 9. Complaint Table
CREATE TABLE Complaint (
    Complaint_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT,
    Description TEXT,
    Status VARCHAR(50),
    FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID)
);

-- 10. Grocery_Expense Table
CREATE TABLE Grocery_Expense (
    Expense_ID INT AUTO_INCREMENT PRIMARY KEY,
    Item_Name VARCHAR(100),
    Quantity INT,
    Cost DECIMAL(10,2),
    Total_Cost DECIMAL(10,2),
    Purchase_Date DATE,
    Hostel_ID INT,
    FOREIGN KEY (Hostel_ID) REFERENCES Hostel(Hostel_ID)
);

-- 11. Food_Menu Table
CREATE TABLE Food_Menu (
    Menu_ID INT AUTO_INCREMENT PRIMARY KEY,
    Day_of_Week VARCHAR(20),
    Meal_Type VARCHAR(20),
    Item_Name VARCHAR(100),
    Hostel_ID INT,
    FOREIGN KEY (Hostel_ID) REFERENCES Hostel(Hostel_ID)
);
