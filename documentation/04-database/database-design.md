\# BizPay Database Design



\*\*Project:\*\* BizPay

\*\*Document:\*\* Database Design

\*\*Version:\*\* 0.1.0

\*\*Status:\*\* Initial Database Design

\*\*Last Updated:\*\* 12 August 2026



\---



\# 1. Introduction



This document defines the initial relational database architecture for BizPay.



The database will use MySQL and will be accessed through the Laravel backend.



The database design must support:



\* Multiple businesses

\* Multiple branches

\* Multiple users

\* Role-based permissions

\* Customers

\* Products

\* Inventory

\* Sales

\* Payments

\* Configuration

\* Audit logging



The design must also support future expansion without requiring major restructuring.



\---



\# 2. Database Technology



The initial database technology will be:



\*\*MySQL 8\*\*



The Laravel application will communicate with MySQL through Laravel's database layer and Eloquent ORM.



The mobile and web applications will never connect directly to MySQL.



Architecture:



```text

Flutter Mobile

&#x20;     |

&#x20;     ↓

Laravel API

&#x20;     |

&#x20;     ↓

Eloquent ORM

&#x20;     |

&#x20;     ↓

MySQL

```



\---



\# 3. Multi-Tenant Data Model



BizPay will use a shared-database, shared-schema multi-tenant architecture initially.



This means multiple businesses will use the same database structure while their data remains logically isolated.



Example:



```text

MySQL

&#x20;|

&#x20;+-- Business A

&#x20;|     |

&#x20;|     +-- Branches

&#x20;|     +-- Users

&#x20;|     +-- Customers

&#x20;|     +-- Products

&#x20;|     +-- Sales

&#x20;|     +-- Payments

&#x20;|

&#x20;+-- Business B

&#x20;      |

&#x20;      +-- Branches

&#x20;      +-- Users

&#x20;      +-- Customers

&#x20;      +-- Products

&#x20;      +-- Sales

&#x20;      +-- Payments

```



Business-owned records will be associated with the appropriate business.



The application layer will enforce tenant isolation.



\---



\# 4. Primary Keys



The initial design will use numeric auto-incrementing primary keys for internal database relationships.



Example:



```text

id BIGINT UNSIGNED

```



For externally exposed resources, public identifiers may be introduced later using UUIDs or ULIDs.



This provides a distinction between:



\* Internal database identifiers

\* Public-facing resource identifiers



\---



\# 5. Core Entity Relationship



The high-level relationship is:



```text

Business

&#x20;  |

&#x20;  +---- Branches

&#x20;  |

&#x20;  +---- Users

&#x20;  |

&#x20;  +---- Customers

&#x20;  |

&#x20;  +---- Categories

&#x20;  |

&#x20;  +---- Products

&#x20;  |

&#x20;  +---- Sales

&#x20;  |

&#x20;  +---- Payments

&#x20;  |

&#x20;  +---- Configurations

&#x20;  |

&#x20;  +---- Audit Logs

```



\---



\# 6. Businesses Table



Table:



```text

businesses

```



Purpose:



Stores organizations using the BizPay platform.



Proposed fields:



| Field               | Type            | Description                     |

| ------------------- | --------------- | ------------------------------- |

| id                  | BIGINT UNSIGNED | Primary key                     |

| name                | VARCHAR(255)    | Business name                   |

| slug                | VARCHAR(255)    | Unique business identifier      |

| registration\_number | VARCHAR(100)    | Business registration reference |

| phone               | VARCHAR(30)     | Business phone                  |

| email               | VARCHAR(255)    | Business email                  |

| address             | TEXT            | Business address                |

| logo\_path           | VARCHAR(255)    | Logo location                   |

| currency            | VARCHAR(10)     | Currency code                   |

| timezone            | VARCHAR(100)    | Business timezone               |

| status              | VARCHAR(30)     | Business status                 |

| created\_at          | TIMESTAMP       | Creation time                   |

| updated\_at          | TIMESTAMP       | Last update                     |



Possible statuses:



```text

active

suspended

pending

inactive

```



\---



\# 7. Branches Table



Table:



```text

branches

```



Purpose:



Allows a business to operate multiple locations.



Fields:



| Field       | Type            | Description       |

| ----------- | --------------- | ----------------- |

| id          | BIGINT UNSIGNED | Primary key       |

| business\_id | BIGINT UNSIGNED | Owning business   |

| name        | VARCHAR(255)    | Branch name       |

| code        | VARCHAR(50)     | Branch identifier |

| phone       | VARCHAR(30)     | Branch phone      |

| address     | TEXT            | Branch address    |

| status      | VARCHAR(30)     | Branch status     |

| created\_at  | TIMESTAMP       | Creation time     |

| updated\_at  | TIMESTAMP       | Last update       |



Relationship:



```text

Business 1 ─────── \* Branches

```



\---



\# 8. Users Table



Table:



```text

users

```



Purpose:



Stores users who access BizPay.



A user may operate at the platform level or within a business.



Fields:



| Field         | Type                 | Description         |

| ------------- | -------------------- | ------------------- |

| id            | BIGINT UNSIGNED      | Primary key         |

| business\_id   | BIGINT UNSIGNED NULL | Associated business |

| branch\_id     | BIGINT UNSIGNED NULL | Default branch      |

| name          | VARCHAR(255)         | Full name           |

| email         | VARCHAR(255)         | Email               |

| phone         | VARCHAR(30)          | Phone               |

| password      | VARCHAR(255)         | Hashed password     |

| status        | VARCHAR(30)          | Account status      |

| last\_login\_at | TIMESTAMP NULL       | Last login          |

| created\_at    | TIMESTAMP            | Creation time       |

| updated\_at    | TIMESTAMP            | Last update         |



`business\_id` may be NULL for platform-level users such as system administrators.



\---



\# 9. Roles Table



Table:



```text

roles

```



Purpose:



Defines user roles.



Fields:



| Field          | Type                 | Description                    |

| -------------- | -------------------- | ------------------------------ |

| id             | BIGINT UNSIGNED      | Primary key                    |

| business\_id    | BIGINT UNSIGNED NULL | Owning business if custom role |

| name           | VARCHAR(100)         | Role name                      |

| description    | TEXT                 | Role description               |

| is\_system\_role | BOOLEAN              | System-defined role            |

| created\_at     | TIMESTAMP            | Creation time                  |

| updated\_at     | TIMESTAMP            | Last update                    |



Example roles:



```text

System Administrator

Business Owner

Manager

Cashier

Accountant

```



\---



\# 10. Permissions Table



Table:



```text

permissions

```



Purpose:



Defines individual system capabilities.



Examples:



```text

view\_customers

create\_customer

edit\_customer

delete\_customer



view\_products

create\_product

edit\_product

delete\_product



create\_sale

view\_sales



process\_payment

view\_payments



manage\_inventory

view\_reports

manage\_users

manage\_business

manage\_configuration

```



Fields:



| Field       | Type            | Description       |

| ----------- | --------------- | ----------------- |

| id          | BIGINT UNSIGNED | Primary key       |

| name        | VARCHAR(150)    | Permission name   |

| description | TEXT            | Description       |

| module      | VARCHAR(100)    | Associated module |

| created\_at  | TIMESTAMP       | Creation time     |

| updated\_at  | TIMESTAMP       | Last update       |



\---



\# 11. Role-Permissions Table



Table:



```text

role\_permissions

```



Purpose:



Creates a many-to-many relationship between roles and permissions.



Fields:



| Field         | Type            | Description |

| ------------- | --------------- | ----------- |

| role\_id       | BIGINT UNSIGNED | Role        |

| permission\_id | BIGINT UNSIGNED | Permission  |



Relationship:



```text

Role \* ─────── \* Permission

```



Example:



```text

Cashier

&#x20;  |

&#x20;  +-- view\_customers

&#x20;  +-- create\_customer

&#x20;  +-- create\_sale

&#x20;  +-- process\_payment

```



\---



\# 12. User-Roles Relationship



A user may have one or more roles.



The initial implementation should support flexible role assignment.



Proposed table:



```text

user\_roles

```



Fields:



| Field     | Type                 | Description           |

| --------- | -------------------- | --------------------- |

| user\_id   | BIGINT UNSIGNED      | User                  |

| role\_id   | BIGINT UNSIGNED      | Role                  |

| branch\_id | BIGINT UNSIGNED NULL | Optional branch scope |



This allows a user to have different responsibilities in different branches if supported.



\---



\# 13. Customers Table



Table:



```text

customers

```



Purpose:



Stores customer information.



Fields:



| Field         | Type              | Description        |

| ------------- | ----------------- | ------------------ |

| id            | BIGINT UNSIGNED   | Primary key        |

| business\_id   | BIGINT UNSIGNED   | Owning business    |

| name          | VARCHAR(255)      | Customer name      |

| phone         | VARCHAR(30)       | Customer phone     |

| email         | VARCHAR(255) NULL | Email              |

| address       | TEXT NULL         | Address            |

| customer\_code | VARCHAR(100) NULL | Internal reference |

| status        | VARCHAR(30)       | Customer status    |

| created\_at    | TIMESTAMP         | Creation time      |

| updated\_at    | TIMESTAMP         | Last update        |



Relationship:



```text

Business 1 ─────── \* Customers

```



\---



\# 14. Categories Table



Table:



```text

categories

```



Purpose:



Groups products.



Fields:



| Field       | Type            | Description     |

| ----------- | --------------- | --------------- |

| id          | BIGINT UNSIGNED | Primary key     |

| business\_id | BIGINT UNSIGNED | Owning business |

| name        | VARCHAR(255)    | Category name   |

| description | TEXT NULL       | Description     |

| status      | VARCHAR(30)     | Status          |

| created\_at  | TIMESTAMP       | Creation time   |

| updated\_at  | TIMESTAMP       | Last update     |



\---



\# 15. Products Table



Table:



```text

products

```



Purpose:



Stores products sold by businesses.



Fields:



| Field         | Type                 | Description         |

| ------------- | -------------------- | ------------------- |

| id            | BIGINT UNSIGNED      | Primary key         |

| business\_id   | BIGINT UNSIGNED      | Owning business     |

| category\_id   | BIGINT UNSIGNED NULL | Category            |

| name          | VARCHAR(255)         | Product name        |

| sku           | VARCHAR(100) NULL    | SKU                 |

| barcode       | VARCHAR(100) NULL    | Barcode             |

| description   | TEXT NULL            | Description         |

| cost\_price    | DECIMAL(15,2)        | Cost                |

| selling\_price | DECIMAL(15,2)        | Selling price       |

| reorder\_level | DECIMAL(15,3)        | Low-stock threshold |

| status        | VARCHAR(30)          | Product status      |

| created\_at    | TIMESTAMP            | Creation time       |

| updated\_at    | TIMESTAMP            | Last update         |



\---



\# 16. Inventory Table



Table:



```text

inventory

```



Purpose:



Stores current stock quantities by branch and product.



Fields:



| Field       | Type            | Description      |

| ----------- | --------------- | ---------------- |

| id          | BIGINT UNSIGNED | Primary key      |

| business\_id | BIGINT UNSIGNED | Business         |

| branch\_id   | BIGINT UNSIGNED | Branch           |

| product\_id  | BIGINT UNSIGNED | Product          |

| quantity    | DECIMAL(15,3)   | Current quantity |

| created\_at  | TIMESTAMP       | Creation time    |

| updated\_at  | TIMESTAMP       | Last update      |



A unique constraint should prevent duplicate inventory records for the same product and branch.



Example:



```text

UNIQUE(branch\_id, product\_id)

```



\---



\# 17. Inventory Movements



Current stock alone is not sufficient for proper auditing.



A future or initial implementation should maintain:



```text

inventory\_movements

```



Possible movement types:



```text

purchase

sale

adjustment

return

transfer

damage

```



Fields may include:



\* id

\* business\_id

\* branch\_id

\* product\_id

\* type

\* quantity

\* reference\_type

\* reference\_id

\* user\_id

\* notes

\* created\_at



This provides an audit trail for stock changes.



\---



\# 18. Sales Table



Table:



```text

sales

```



Purpose:



Stores completed or pending sales.



Fields:



| Field       | Type                 | Description    |

| ----------- | -------------------- | -------------- |

| id          | BIGINT UNSIGNED      | Primary key    |

| business\_id | BIGINT UNSIGNED      | Business       |

| branch\_id   | BIGINT UNSIGNED      | Branch         |

| user\_id     | BIGINT UNSIGNED      | Cashier/user   |

| customer\_id | BIGINT UNSIGNED NULL | Customer       |

| sale\_number | VARCHAR(100)         | Sale reference |

| subtotal    | DECIMAL(15,2)        | Subtotal       |

| discount    | DECIMAL(15,2)        | Discount       |

| tax         | DECIMAL(15,2)        | Tax            |

| total       | DECIMAL(15,2)        | Total          |

| status      | VARCHAR(30)          | Sale status    |

| created\_at  | TIMESTAMP            | Creation time  |

| updated\_at  | TIMESTAMP            | Last update    |



Possible statuses:



```text

pending

completed

cancelled

refunded

```



\---



\# 19. Sale Items Table



Table:



```text

sale\_items

```



Purpose:



Stores individual products within a sale.



Fields:



| Field      | Type            | Description   |

| ---------- | --------------- | ------------- |

| id         | BIGINT UNSIGNED | Primary key   |

| sale\_id    | BIGINT UNSIGNED | Sale          |

| product\_id | BIGINT UNSIGNED | Product       |

| quantity   | DECIMAL(15,3)   | Quantity      |

| unit\_price | DECIMAL(15,2)   | Price at sale |

| discount   | DECIMAL(15,2)   | Discount      |

| subtotal   | DECIMAL(15,2)   | Line subtotal |

| created\_at | TIMESTAMP       | Creation time |

| updated\_at | TIMESTAMP       | Last update   |



Important:



The `unit\_price` is stored in the sale item rather than relying on the current product price.



This preserves historical transaction accuracy.



\---



\# 20. Payments Table



Table:



```text

payments

```



Purpose:



Stores payment transactions.



Fields:



| Field              | Type                 | Description                   |

| ------------------ | -------------------- | ----------------------------- |

| id                 | BIGINT UNSIGNED      | Primary key                   |

| business\_id        | BIGINT UNSIGNED      | Business                      |

| branch\_id          | BIGINT UNSIGNED      | Branch                        |

| sale\_id            | BIGINT UNSIGNED      | Sale                          |

| customer\_id        | BIGINT UNSIGNED NULL | Customer                      |

| provider           | VARCHAR(50)          | Payment provider              |

| method             | VARCHAR(50)          | Payment method                |

| amount             | DECIMAL(15,2)        | Amount                        |

| reference          | VARCHAR(150)         | Internal reference            |

| provider\_reference | VARCHAR(255) NULL    | Provider transaction ID       |

| phone              | VARCHAR(30) NULL     | Customer phone                |

| status             | VARCHAR(30)          | Payment status                |

| metadata           | JSON NULL            | Provider-specific information |

| paid\_at            | TIMESTAMP NULL       | Payment completion            |

| created\_at         | TIMESTAMP            | Creation time                 |

| updated\_at         | TIMESTAMP            | Last update                   |



Possible providers:



```text

mpesa

airtel

cash

```



Possible statuses:



```text

pending

successful

failed

cancelled

expired

refunded

```



\---



\# 21. Payment References and Idempotency



Payment processing must prevent duplicate transaction recording.



The system should maintain unique references where appropriate.



Examples:



```text

internal payment reference

provider transaction reference

request reference

callback reference

```



Provider-specific transaction IDs should be stored and protected with appropriate uniqueness constraints where possible.



This is especially important when payment providers retry callbacks.



\---



\# 22. Configurations Table



Table:



```text

configurations

```



Purpose:



Stores dynamic application and business configuration.



Possible fields:



| Field       | Type                 | Description         |

| ----------- | -------------------- | ------------------- |

| id          | BIGINT UNSIGNED      | Primary key         |

| business\_id | BIGINT UNSIGNED NULL | Business scope      |

| branch\_id   | BIGINT UNSIGNED NULL | Branch scope        |

| key         | VARCHAR(150)         | Configuration key   |

| value       | JSON                 | Configuration value |

| type        | VARCHAR(50)          | Value type          |

| created\_at  | TIMESTAMP            | Creation time       |

| updated\_at  | TIMESTAMP            | Last update         |



Examples:



```text

business.name

business.logo

theme.primary\_color

theme.secondary\_color

feature.inventory

feature.loyalty

feature.expenses

payment.mpesa.enabled

payment.airtel.enabled

receipt.show\_logo

```



\---



\# 23. Configuration Resolution



Configuration will follow an inheritance model.



Example:



```text

System Default

&#x20;     ↓

Business Configuration

&#x20;     ↓

Branch Configuration

```



The most specific available configuration should take precedence.



Example:



```text

System:

inventory = false



Business A:

inventory = true



Branch A:

inventory = false

```



Effective result for Branch A:



```text

inventory = false

```



\---



\# 24. Audit Logs Table



Table:



```text

audit\_logs

```



Purpose:



Records important system events.



Fields:



| Field       | Type                 | Description        |

| ----------- | -------------------- | ------------------ |

| id          | BIGINT UNSIGNED      | Primary key        |

| business\_id | BIGINT UNSIGNED NULL | Business           |

| user\_id     | BIGINT UNSIGNED NULL | User               |

| action      | VARCHAR(150)         | Action             |

| entity\_type | VARCHAR(150) NULL    | Resource type      |

| entity\_id   | BIGINT UNSIGNED NULL | Resource ID        |

| old\_values  | JSON NULL            | Previous state     |

| new\_values  | JSON NULL            | New state          |

| ip\_address  | VARCHAR(45) NULL     | IP                 |

| user\_agent  | TEXT NULL            | Client information |

| created\_at  | TIMESTAMP            | Event time         |



Examples:



```text

business.updated

user.created

role.updated

payment.created

payment.completed

configuration.updated

inventory.adjusted

```



\---



\# 25. Entity Relationship Diagram



High-level relationship:



```text

&#x20;                        BUSINESSES

&#x20;                            |

&#x20;         +------------------+------------------+

&#x20;         |                  |                  |

&#x20;         ▼                  ▼                  ▼

&#x20;      BRANCHES            USERS           CUSTOMERS

&#x20;         |                  |

&#x20;         |                  ▼

&#x20;         |                ROLES

&#x20;         |                  |

&#x20;         |                  ▼

&#x20;         |             PERMISSIONS

&#x20;         |

&#x20;         +------------------+

&#x20;         |

&#x20;         ▼

&#x20;      INVENTORY

&#x20;         |

&#x20;         ▼

&#x20;      PRODUCTS

&#x20;         |

&#x20;      CATEGORIES



BUSINESS

&#x20;  |

&#x20;  ▼

&#x20;SALES

&#x20;  |

&#x20;  +──────────────► SALE ITEMS

&#x20;  |

&#x20;  ▼

&#x20;PAYMENTS



BUSINESS

&#x20;  |

&#x20;  +──────────────► CONFIGURATIONS

&#x20;  |

&#x20;  +──────────────► AUDIT LOGS

```



\---



\# 26. Key Relationships



\## Business



```text

Business 1 ─── \* Branches

Business 1 ─── \* Users

Business 1 ─── \* Customers

Business 1 ─── \* Categories

Business 1 ─── \* Products

Business 1 ─── \* Sales

Business 1 ─── \* Payments

Business 1 ─── \* Configurations

Business 1 ─── \* Audit Logs

```



\## Branch



```text

Branch 1 ─── \* Users

Branch 1 ─── \* Sales

Branch 1 ─── \* Inventory

Branch 1 ─── \* Payments

```



\## Product



```text

Category 1 ─── \* Products

Product 1 ─── \* Inventory

Product 1 ─── \* Sale Items

```



\## Sale



```text

Sale 1 ─── \* Sale Items

Sale 1 ─── \* Payments

```



\---



\# 27. Data Isolation



Every request involving business-owned data must be evaluated against the authenticated user's business context.



Conceptually:



```text

Authenticated User

&#x20;       ↓

Determine Business

&#x20;       ↓

Determine Branch Access

&#x20;       ↓

Apply Authorization

&#x20;       ↓

Query Business Data

```



The system must not rely solely on frontend filtering to provide tenant isolation.



Tenant isolation must be enforced by the backend.



\---



\# 28. Indexing Strategy



Important indexes should be created for frequently queried fields.



Potential indexes include:



```text

business\_id

branch\_id

user\_id

customer\_id

product\_id

sale\_id

payment reference

provider\_reference

status

created\_at

```



Composite indexes may be used where queries commonly combine fields.



Example:



```text

business\_id + status

business\_id + created\_at

branch\_id + created\_at

```



Indexes will be evaluated during implementation and performance testing.



\---



\# 29. Foreign Key Strategy



Foreign keys should be used where appropriate to maintain referential integrity.



Examples:



```text

branches.business\_id

users.business\_id

users.branch\_id

products.business\_id

products.category\_id

inventory.business\_id

inventory.branch\_id

inventory.product\_id

sales.business\_id

sales.branch\_id

sales.user\_id

sales.customer\_id

sale\_items.sale\_id

sale\_items.product\_id

payments.sale\_id

payments.business\_id

payments.branch\_id

```



Deletion behavior will be carefully selected.



Financial records should generally not be physically deleted merely because a related business object becomes inactive.



\---



\# 30. Financial Data Integrity



Financial records must be treated as historical records.



The system should avoid destructive deletion of:



\* Sales

\* Payments

\* Payment references

\* Important inventory movements



Where possible, records should be:



\* Cancelled

\* Reversed

\* Refunded

\* Deactivated



rather than deleted.



\---



\# 31. Monetary Data



Money values will use:



```text

DECIMAL(15,2)

```



Floating-point values should not be used for financial calculations.



Example:



```text

amount = 1500.00

```



Currency will initially be stored using ISO-style currency codes such as:



```text

KES

```



\---



\# 32. Timestamps



The system will store timestamps consistently.



The application should use UTC internally where practical and convert timestamps to the appropriate business or user timezone for presentation.



The business timezone will be stored in the `businesses` table.



\---



\# 33. Soft Deletes



Soft deletion may be used for appropriate entities such as:



\* Products

\* Customers

\* Users

\* Categories



Soft deletion should not be used as a substitute for proper financial transaction states.



Sales and payments should maintain historical integrity.



\---



\# 34. Future Database Expansion



Potential future entities include:



```text

suppliers

purchases

purchase\_items

expenses

expense\_categories

loyalty\_accounts

loyalty\_transactions

refunds

stock\_transfers

notifications

subscriptions

invoices

branches\_settings

payment\_provider\_configs

```



These will be added only when the relevant functionality is introduced.



\---



\# 35. Database Design Principles



The database will follow:



1\. Data integrity

2\. Referential integrity

3\. Tenant isolation

4\. Appropriate normalization

5\. Proper indexing

6\. Historical transaction preservation

7\. Secure handling of sensitive information

8\. Extensibility

9\. Performance awareness

10\. Clear relationships



\---



\# 36. Implementation Strategy



The database will be implemented through Laravel migrations.



The process will be:



```text

Database Design

&#x20;     ↓

Migration Design

&#x20;     ↓

Laravel Migrations

&#x20;     ↓

Migration Testing

&#x20;     ↓

Eloquent Models

&#x20;     ↓

Relationships

&#x20;     ↓

Seeders

&#x20;     ↓

Database Testing

```



No production database changes will be made manually where migrations can provide a controlled and repeatable process.



\---



\# 37. Current Status



Completed:



\* Requirements

\* System architecture

\* Initial database architecture



Next:



\* Review database relationships

\* Create Laravel backend

\* Configure MySQL

\* Implement migrations

\* Implement Eloquent models

\* Create initial seed data



\---



\# 38. Database Design Evolution



This document represents the initial database architecture.



The design may evolve during implementation.



Any significant changes must be reflected in:



\* This document

\* Laravel migrations

\* Eloquent models

\* API documentation

\* Architecture documentation

\* Changelog



The database structure and application behavior must remain synchronized.



