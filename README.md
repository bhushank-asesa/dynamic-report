# Dynamic Report System Design

## Database Design

### Main Location

* id
* name
* status
* timestamp

### Sub Location

* id
* mainLocationId
* name
* status
* timestamp

### Expense Type

* id
* name
* status
* timestamp

### Expense Category

* id
* name
* status
* timestamp

### Users

* id
* firstName
* lastName
* username
* email
* mainLocationId
* subLocationId
* address
* profilePic
* status
* password
* timestamp

### Expenses List

* id
* mainLocationId
* subLocationId
* expenseCategoryId
* expenseTypeId
* userId
* title
* note
* amount
* transactionId
* status
* timestamp 

## Report

### Parameter

#### Comparision 

* Different Location/Expenses
* Time Period

#### Rank

#### Duration

#### Grouping/Field

#### Sort

#### Search

#### Percentage