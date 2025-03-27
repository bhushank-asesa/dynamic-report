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

---

### Expnese by group (ExpenseCategory)

#### Request

```json
{
    "groupBy": [
        "expenseCategoryId"
    ],
    "limit":5,
    "sortAmount":"desc"
}
```

####  optional request parameter/keys

* sortAmount => asc/desc
* maxAmount => numeric greater than 0
* minAmount => numeric greater than 0 
* expenseCategoryId => array of expense category id
* fromDate => start date for report
* toDate => end date for report
* subRecords => yes/no

#### Response

```json
{
  "status": true,
  "message": "Reports data found",
  "data": [
    {
      "amount": 1399677,
      "expenseCategoryName": "Salary",
      "expenseCategoryId": 1,
      "subRecordsCount": 95,
      "conditionalPercentage": "84.52",
      "conditionalWithLimitPercentage": "86.82",
      "percentage": "84.52"
    },
    {
      "amount": 58277,
      "expenseCategoryName": "Canteen",
      "expenseCategoryId": 2,
      "subRecordsCount": 54,
      "conditionalPercentage": "3.52",
      "conditionalWithLimitPercentage": "3.61",
      "percentage": "3.52"
    },
    {
      "amount": 53200,
      "expenseCategoryName": "Electronics",
      "expenseCategoryId": 6,
      "subRecordsCount": 58,
      "conditionalPercentage": "3.21",
      "conditionalWithLimitPercentage": "3.30",
      "percentage": "3.21"
    },
    {
      "amount": 51499,
      "expenseCategoryName": "Property Maintainence",
      "expenseCategoryId": 3,
      "subRecordsCount": 46,
      "conditionalPercentage": "3.11",
      "conditionalWithLimitPercentage": "3.19",
      "percentage": "3.11"
    },
    {
      "amount": 49588,
      "expenseCategoryName": "Taxes",
      "expenseCategoryId": 5,
      "subRecordsCount": 50,
      "conditionalPercentage": "2.99",
      "conditionalWithLimitPercentage": "3.08",
      "percentage": "2.99"
    }
  ],
  "count": 5,
  "conditionalWithLimitTotalAmount": 1612241,
  "totalAmount": 1655980,
  "conditionalPercentage": 1655980
}
```

---

### Expnese by group (ExpenseCategory) `Details`

#### Request

```json
{
    "expenseCategoryId":[1],
    "limit":5,
    "sortAmount":"desc"
}
```

* expenseCategoryId should be selected bar/item of parent report (Expnese by group)

#### Response

```json
{
  "status": true, 
  "message": "Reports data found", 
  "data": [
    {
      "id": 2,
      "transactionId": "DH7BS7835",
      "amount": 820,
      "dateOfExpense": "2024-12-09",
      "title": "Electronics",
      "expenseCategoryName": "Electronics",
      "expenseTypeName": "Indirect",
      "userName": null,
      "mainLocationName": "Nashik",
      "subLocationName": "Nashik"
    }
  ], 
  "count": 1, 
  "conditionalWithLimitTotalAmount": 820, 
  "totalAmount": 1655980, 
  "conditionalTotalAmount": 820
}
```