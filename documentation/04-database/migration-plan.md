BizPay Database Migration Plan



Project: BizPay

Document: Database Migration Plan

Version: 0.1.0

Status: Initial Migration Plan

Date: 12 August 2026



1\. Purpose



This document defines the implementation order for BizPay's database migrations.



The migration plan converts the approved database architecture into actual Laravel database structures.



The database must support:



Multiple businesses

Multiple branches

Multiple users

Configurable roles

Configurable permissions

Customers

Products

Inventory

Sales

Payments

Dynamic business configuration

Audit logging

2\. Migration Philosophy



BizPay will use Laravel migrations as the source of truth for database structure.



Database changes must not be made manually in production.



Instead:



Laravel Migration

&#x20;      ↓

Database Schema

&#x20;      ↓

Application Models

&#x20;      ↓

API



Every structural database change should be represented by a migration.



This provides:



Version control

Reproducibility

Easier deployment

Team collaboration

Rollback capability

Database history

3\. Existing Laravel Tables



Laravel has already created the initial framework tables.



Current tables:



users

cache

cache\_locks

failed\_jobs

job\_batches

jobs

migrations

password\_reset\_tokens

sessions



The users table will be extended carefully rather than unnecessarily replaced.



4\. BizPay Migration Groups



The BizPay database will be implemented in logical groups.



GROUP 1

Business Foundation



GROUP 2

User and Access Control



GROUP 3

Business Operations



GROUP 4

Inventory



GROUP 5

Sales



GROUP 6

Payments



GROUP 7

Configuration



GROUP 8

Audit and Supporting Systems

5\. Group 1 — Business Foundation



The first BizPay-specific tables will establish the tenant structure.



Tables:



businesses

branches



Relationship:



BUSINESS

&#x20;  │

&#x20;  └── has many BRANCHES

6\. Businesses Table



The businesses table represents an organization using BizPay.



Initial fields:



id

name

slug

business\_type

email

phone

address

status

created\_at

updated\_at



Potential future fields:



logo

website

tax\_number

currency

timezone



These will be added when their requirements are finalized.



7\. Business Status



Businesses will have a status.



Initial conceptual states:



active

inactive

suspended

pending



The exact implementation may use a string or enum depending on future requirements.



8\. Business Slug



Each business should have a unique slug.



Example:



ABC Supermarket



may become:



abc-supermarket



The slug can be useful for:



URLs

Business identification

Administration interfaces

Public business references

9\. Branches Table



The branches table represents physical or logical business locations.



Initial fields:



id

business\_id

name

code

phone

email

address

status

created\_at

updated\_at



Relationship:



Business 1 ───────── \* Branches

10\. Branch Code



Each branch should have a business-specific code.



Example:



Business:

ABC Supermarket



Branches:



NRB-01

KSM-01

NKR-01



The branch code should be unique within the business.



11\. Group 2 — User and Access Control



The second migration group establishes authorization.



Tables:



roles

permissions

role\_permissions

user\_roles

user\_businesses



The system will use relationships rather than hard-coded authorization rules.



12\. Roles Table



The roles table represents a collection of permissions.



Initial fields:



id

name

slug

description

scope

created\_at

updated\_at



The scope field helps distinguish platform-level and business-level roles.



Conceptually:



platform

business

13\. Permissions Table



Permissions represent individual actions.



Initial fields:



id

name

slug

module

description

created\_at

updated\_at



Examples:



users.view

users.create

users.update

users.delete



products.view

products.create

products.update

products.delete



sales.view

sales.create



payments.view

payments.create

payments.refund

14\. Role-Permission Relationship



Roles and permissions have a many-to-many relationship.



ROLE

&#x20;│

&#x20;▼

ROLE\_PERMISSIONS

&#x20;│

&#x20;▼

PERMISSION



The relationship table contains:



role\_id

permission\_id



The combination should be unique.



15\. User-Business Relationship



BizPay should avoid unnecessarily restricting a user to one business.



The planned structure is:



USER

&#x20;│

&#x20;▼

USER\_BUSINESSES

&#x20;│

&#x20;▼

BUSINESS



The user\_businesses table may contain:



id

user\_id

business\_id

status

created\_at

updated\_at



This allows future support for users who work across multiple businesses.



Example:



User Eddy

&#x20;  │

&#x20;  ├── Business A

&#x20;  └── Business B

16\. User Role Relationship



Roles may also be assigned through a relationship table.



Conceptually:



USER

&#x20;│

&#x20;▼

USER\_ROLES

&#x20;│

&#x20;▼

ROLE



The initial relationship may contain:



id

user\_id

role\_id

business\_id

branch\_id

created\_at

updated\_at



The inclusion of business\_id and optional branch\_id allows roles to be scoped.



Example:



Eddy

&#x20;└── Manager

&#x20;     └── Business A

&#x20;          └── Nairobi Branch

17\. Role Scope



Roles may operate at different levels.



Platform

Business

Branch



Example:



Platform Administrator

&#x20;   → Platform



Business Owner

&#x20;   → Business



Branch Manager

&#x20;   → Branch



The final authorization implementation will determine how these scopes interact.



18\. User Table Changes



The existing Laravel users table should remain the foundation for authentication.



It will eventually be extended with fields required by BizPay.



Potential fields include:



phone

status

last\_login\_at



However, fields will only be added when they have a defined business purpose.



We will avoid adding unnecessary columns.



19\. Group 3 — Customers



After authentication and business access are established:



customers



will be introduced.



A customer belongs to a business.



Initial fields:



id

business\_id

name

phone

email

address

status

created\_at

updated\_at



The phone number is particularly important because BizPay's payment workflow may use it to initiate mobile-money payment requests.



20\. Group 4 — Product Foundation



Product management will use:



categories

products



Relationship:



CATEGORY

&#x20;   │

&#x20;   └── PRODUCTS



A product belongs to a business.



21\. Products



Initial fields:



id

business\_id

category\_id

name

sku

description

price

cost\_price

status

created\_at

updated\_at



The final financial model may evolve as requirements become clearer.



22\. Inventory



Inventory will be branch-specific.



Initial relationship:



BUSINESS

&#x20;   │

&#x20;   └── BRANCH

&#x20;         │

&#x20;         └── INVENTORY

&#x20;               │

&#x20;               └── PRODUCT



Initial fields:



id

business\_id

branch\_id

product\_id

quantity

minimum\_quantity

created\_at

updated\_at



The combination of:



branch\_id

product\_id



should be unique.



23\. Inventory Movements



Inventory quantity alone is not enough for reliable stock management.



BizPay will eventually maintain an inventory movement history.



Examples:



purchase

sale

return

damage

adjustment

transfer



Initial conceptual fields:



id

business\_id

branch\_id

product\_id

type

quantity

reference\_type

reference\_id

notes

created\_by

created\_at

updated\_at



This creates an audit trail for stock changes.



24\. Group 5 — Sales



Sales will be represented by:



sales

sale\_items



Relationship:



SALE

&#x20;│

&#x20;└── SALE\_ITEMS



A sale may optionally be associated with a customer.



25\. Sales Table



Initial fields:



id

business\_id

branch\_id

user\_id

customer\_id

sale\_number

subtotal

discount

tax

total

status

created\_at

updated\_at



The final tax and discount model will be refined during implementation.



26\. Sale Items



Initial fields:



id

sale\_id

product\_id

quantity

unit\_price

discount

subtotal

created\_at

updated\_at



Historical pricing must be preserved.



If a product price changes later, existing sale records must not change.



27\. Group 6 — Payments



Payments are a critical part of BizPay.



The initial payment architecture will separate:



payment\_requests

payments



This distinction is intentional.



28\. Payment Requests



A payment request represents an attempt to initiate a payment.



Initial conceptual fields:



id

business\_id

branch\_id

sale\_id

customer\_id

provider

phone

amount

reference

status

requested\_at

created\_at

updated\_at



Possible statuses:



pending

successful

failed

cancelled

expired

29\. Payments



The payments table represents a confirmed payment transaction.



Initial conceptual fields:



id

business\_id

branch\_id

sale\_id

payment\_request\_id

provider

provider\_reference

amount

status

paid\_at

created\_at

updated\_at



The provider reference must be retained for reconciliation.



30\. Payment Provider Abstraction



Payment provider logic must not be hard-coded into sales controllers.



Instead:



Payment Service

&#x20;      │

&#x20;      ├── M-Pesa Provider

&#x20;      │

&#x20;      └── Airtel Money Provider



This allows additional providers to be introduced later.



Potential future providers:



Bank

Card

PayPal

Other Mobile Money Providers

31\. Payment Security



Payment provider secrets must never be stored in source code.



They will eventually be stored using secure server-side configuration.



The mobile application must never receive provider secret credentials.



Correct:



Mobile

&#x20; ↓

BizPay API

&#x20; ↓

Payment Service

&#x20; ↓

Provider

32\. Group 7 — Configuration



BizPay requires dynamic configuration.



A configuration system will eventually allow administrators to control business settings without modifying source code.



Conceptual table:



configurations



Potential fields:



id

business\_id

branch\_id

key

value

type

created\_at

updated\_at



Examples:



business.name

business.logo

business.theme

receipt.show\_logo

receipt.footer

feature.inventory

feature.customers

payment.default\_provider



Sensitive values require special handling and must not automatically be exposed to clients.



33\. Configuration Precedence



Configuration may exist at different levels:



System

&#x20;  ↓

Business

&#x20;  ↓

Branch



More specific configuration can override general configuration.



Example:



System:

theme = default



Business:

theme = blue



Branch:

theme = dark



The effective branch configuration becomes:



dark

34\. Group 8 — Audit Logs



Important operations will eventually be recorded in:



audit\_logs



Initial conceptual fields:



id

user\_id

business\_id

branch\_id

action

entity\_type

entity\_id

old\_values

new\_values

ip\_address

user\_agent

created\_at



This will support traceability.



35\. Migration Dependency Order



Migrations must respect foreign-key dependencies.



The planned order is:



1\. businesses

&#x20;       ↓

2\. branches

&#x20;       ↓

3\. users extension

&#x20;       ↓

4\. roles

&#x20;       ↓

5\. permissions

&#x20;       ↓

6\. role\_permissions

&#x20;       ↓

7\. user\_businesses

&#x20;       ↓

8\. user\_roles

&#x20;       ↓

9\. customers

&#x20;       ↓

10\. categories

&#x20;       ↓

11\. products

&#x20;       ↓

12\. inventory

&#x20;       ↓

13\. inventory\_movements

&#x20;       ↓

14\. sales

&#x20;       ↓

15\. sale\_items

&#x20;       ↓

16\. payment\_requests

&#x20;       ↓

17\. payments

&#x20;       ↓

18\. configurations

&#x20;       ↓

19\. audit\_logs

36\. Foreign Key Strategy



Foreign keys will be used wherever appropriate.



Example:



branches.business\_id

&#x20;       ↓

businesses.id



and:



products.business\_id

&#x20;       ↓

businesses.id



Foreign keys help maintain database integrity.



37\. Delete Behavior



Deletion behavior must be carefully selected.



For example, deleting a business should not casually destroy financial records.



The project will prefer:



Soft deletion

Status changes

Archiving



where appropriate.



Financial records such as completed payments should generally remain available for audit and reconciliation.



38\. Indexing Strategy



Indexes will be added to fields commonly used for:



Foreign keys

Authentication lookups

Business filtering

Branch filtering

Transaction references

Product SKU searches

Customer phone searches

Payment provider references



Examples:



business\_id

branch\_id

user\_id

customer\_id

product\_id

sale\_number

provider\_reference

phone

sku



Indexes will be introduced deliberately rather than indiscriminately.



39\. Unique Constraints



Important business identifiers should use unique constraints where appropriate.



Examples:



business.slug



Business-specific:



branch.code

product.sku



Payment-specific:



provider\_reference



The exact uniqueness scope will be finalized during migration implementation.



40\. Data Types



Database fields will use appropriate data types.



Examples:



IDs

→ unsigned big integers / Laravel standard IDs



Money

→ decimal



Quantities

→ appropriate decimal/integer types



Statuses

→ controlled values



Timestamps

→ timestamps



Money should not be stored using floating-point types.



41\. Monetary Precision



Financial values will use fixed precision.



For example:



DECIMAL(15,2)



rather than:



FLOAT



This prevents floating-point rounding problems in financial calculations.



42\. Migration Naming



Laravel will generate migration timestamps automatically.



Migration names should clearly describe the change.



Examples:



create\_businesses\_table

create\_branches\_table

create\_roles\_table

create\_permissions\_table

create\_user\_businesses\_table

create\_user\_roles\_table



Future changes should use descriptive migration names.



43\. Migration Rules



Once a migration has been committed and applied to shared environments:



Avoid editing old migrations unnecessarily.

Create a new migration for structural changes.

Test migrations before deployment.

Maintain backwards compatibility where possible.



This preserves the database migration history.



44\. Testing Strategy



Each migration group will be tested after implementation.



Testing will include:



Migration succeeds

&#x20;      ↓

Tables exist

&#x20;      ↓

Foreign keys work

&#x20;      ↓

Constraints work

&#x20;      ↓

Models work

&#x20;      ↓

Relationships work



Where appropriate, automated Laravel tests will be added.



45\. Seed Data



Development seeders will eventually provide safe test data.



Examples:



Platform Admin

Demo Business

Demo Branch

Demo Users

Demo Roles

Demo Permissions

Demo Products

Demo Customers



Seed data will be clearly separated from production data.



46\. Development vs Production



Development credentials and data must never be assumed to be production credentials.



The project will use environment-specific configuration.



Example:



Development

Testing

Staging

Production



Each environment may have different:



Database

API credentials

Payment configuration

Application URL

Logging configuration

47\. Current Database State



At the beginning of this milestone, the database contains Laravel's default tables:



users

cache

cache\_locks

failed\_jobs

job\_batches

jobs

migrations

password\_reset\_tokens

sessions



BizPay-specific tables have not yet been created.



48\. Implementation Strategy



We will not create all BizPay tables at once.



Instead, implementation will proceed incrementally.



First:



businesses

branches



Then:



roles

permissions

user\_businesses

user\_roles



Then we will test the relationships before continuing.



This reduces the risk of building a large number of dependent migrations incorrectly.



49\. First Implementation Target



The first actual BizPay migration will create:



businesses



The second will create:



branches



The resulting relationship will be:



BUSINESS

&#x20;  │

&#x20;  ├── Branch A

&#x20;  ├── Branch B

&#x20;  └── Branch C



After successful testing, the changes will be committed to Git.



50\. Migration Milestone Definition



This database implementation milestone will be considered successful when:



businesses exists

&#x20;       AND

branches exists

&#x20;       AND

foreign key works

&#x20;       AND

relationships are tested

&#x20;       AND

migrations are documented

&#x20;       AND

Git history is updated

51\. Current Status



Architecture:



COMPLETE



Migration plan:



COMPLETE



Implementation:



READY TO BEGIN

52\. Next Step



The next development action is to create the first BizPay migration:



create\_businesses\_table



This will be the first database table created specifically for BizPay.



After it is tested, we will create:



create\_branches\_table



and establish the first real BizPay database relationship.

