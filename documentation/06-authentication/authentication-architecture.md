BizPay Authentication, Authorization \& Multi-Business Architecture



Project: BizPay

Document: Authentication, Authorization \& Multi-Business Architecture

Version: 0.1.0

Status: Architecture Defined

Date: 12 August 2026



1\. Purpose



This document defines the authentication, authorization, user-role, permission, business, and branch architecture for BizPay.



The purpose is to establish a secure foundation before implementing the payment, sales, customer, inventory, mobile, and administration systems.



BizPay is intended to become a scalable platform capable of serving multiple businesses from one application.



The architecture must therefore support:



Multiple businesses

Multiple branches per business

Multiple users

Different user roles

Configurable permissions

Platform-level administration

Business-level administration

Mobile authentication

Web administration authentication

Secure API access

Tenant isolation

2\. Architectural Principle



Authentication and authorization will be handled by the backend.



Neither the mobile application nor the web application will be responsible for making final security decisions.



The clients request an operation from the API, while Laravel determines whether the authenticated user is allowed to perform it.



Mobile App

&#x20;    │

&#x20;    ▼

Laravel API

&#x20;    │

&#x20;    ├── Authentication

&#x20;    ├── Authorization

&#x20;    ├── Business Context

&#x20;    └── Permission Check

&#x20;    │

&#x20;    ▼

Database



This prevents security rules from being duplicated across different client applications.



3\. Platform Structure



BizPay will operate as a multi-business platform.



The high-level structure is:



&#x20;                   BIZPAY PLATFORM

&#x20;                         │

&#x20;                ┌────────┴────────┐

&#x20;                │                 │

&#x20;         PLATFORM ADMIN      PLATFORM STAFF

&#x20;                │

&#x20;                ▼

&#x20;            BUSINESSES

&#x20;                │

&#x20;       ┌────────┼────────┐

&#x20;       │        │        │

&#x20;  Business A Business B Business C

&#x20;       │

&#x20;    BRANCHES

&#x20;       │

&#x20;  ┌────┼─────────────┐

&#x20;  │    │             │

&#x20;Owner Manager      Staff



The platform administrator operates above individual businesses.



A business owner operates within their own business.



4\. Platform Administrator



The Platform Administrator is responsible for managing the BizPay platform itself.



Platform administrators may eventually have access to:



Businesses

Business accounts

Platform users

Subscription plans

System configuration

Payment provider configuration

Platform-wide settings

Feature availability

System monitoring

Audit logs

Support functions

Security controls



The platform administrator must not be treated as an ordinary business user.



5\. Business Owner



A Business Owner manages their own business within BizPay.



A business owner may have access to:



Business profile

Business branches

Business users

Products

Customers

Inventory

Sales

Payments

Reports

Business configuration

Receipt settings

Permitted payment providers



A business owner must not be able to access another business's data.



6\. Manager



A Manager operates within a specific business and potentially a specific branch.



Depending on assigned permissions, a manager may:



Manage staff

View sales

Manage inventory

View reports

Manage customers

Manage products

Process or supervise transactions



Manager privileges will be controlled through permissions rather than relying solely on the role name.



7\. Cashier / Staff



Cashiers and other staff members perform operational activities assigned to them.



Examples include:



Creating sales

Accepting payments

Registering customers

Viewing permitted products

Printing or displaying receipts

Viewing their permitted transaction history



A cashier should not automatically receive administrative privileges.



8\. Roles



BizPay will initially support conceptual roles such as:



Platform Administrator

Platform Staff

Business Owner

Manager

Cashier

Staff



The exact roles may evolve as the platform develops.



Roles will act as collections of permissions.



Role

&#x20; │

&#x20; ├── Permission

&#x20; ├── Permission

&#x20; ├── Permission

&#x20; └── Permission

9\. Permissions



Permissions represent specific actions a user is allowed to perform.



Examples:



users.view

users.create

users.update

users.delete



customers.view

customers.create

customers.update

customers.delete



products.view

products.create

products.update

products.delete



sales.view

sales.create

sales.update

sales.delete



payments.view

payments.create

payments.refund



reports.view



business.update

branch.create

branch.update



Permissions provide more flexibility than hard-coded role checks.



10\. Why Permissions Are Required



BizPay should not rely exclusively on code such as:



if user is admin



because different businesses may require different access structures.



Instead, the system should determine whether the authenticated user has the required permission.



Conceptually:



User

&#x20;│

&#x20;▼

Role

&#x20;│

&#x20;▼

Permissions

&#x20;│

&#x20;▼

Can the requested operation be performed?



This allows permissions to eventually be managed from the administration interface.



11\. Dynamic Administration Principle



A major requirement of BizPay is that administrators should be able to control platform functionality through the web application without modifying the source code.



The intended flow is:



Administrator

&#x20;     │

&#x20;     ▼

Web Administration

&#x20;     │

&#x20;     ▼

Configuration API

&#x20;     │

&#x20;     ▼

Database

&#x20;     │

&#x20;     ▼

Mobile / Web Client



Examples include:



Enabling or disabling features

Changing business branding

Managing users

Managing roles

Assigning permissions

Managing branches

Configuring receipts

Configuring payment providers

Managing business information



The actual implementation will be controlled by backend configuration and authorization rules.



12\. Multi-Tenant Architecture



BizPay will use a multi-business architecture.



Each business represents an independent tenant within the platform.



Conceptually:



BizPay

&#x20;│

&#x20;├── Business A

&#x20;│    ├── Branch 1

&#x20;│    ├── Branch 2

&#x20;│    └── Users

&#x20;│

&#x20;├── Business B

&#x20;│    ├── Branch 1

&#x20;│    └── Users

&#x20;│

&#x20;└── Business C

&#x20;     ├── Branch 1

&#x20;     ├── Branch 2

&#x20;     └── Users



Business-specific information must remain isolated.



13\. Tenant Isolation



Tenant isolation is a backend security responsibility.



For example:



Business A

&#x20;   │

&#x20;   └── Customer A1



Business B

&#x20;   │

&#x20;   └── Customer B1



A user belonging to Business A must never be able to request:



Customer B1



simply by changing an ID in an API request.



Authorization must verify the user's business context before returning data.



14\. Business Context



Authenticated users will have a business context.



Conceptually:



User

&#x20;│

&#x20;├── Business

&#x20;│

&#x20;├── Branch

&#x20;│

&#x20;├── Role

&#x20;│

&#x20;└── Permissions



For users working across multiple branches, the system may support an active branch context.



Example:



Business: ABC Supermarket



Branches:

\- Nairobi

\- Kisumu

\- Nakuru



User:

John



Active Branch:

Nairobi



The backend will enforce access according to the user's assigned business and branch permissions.



15\. Users and Businesses



The initial conceptual relationship is:



Business

&#x20;  │

&#x20;  └── has many Users



User

&#x20;  │

&#x20;  └── belongs to Business



The architecture may later support users belonging to multiple businesses if the platform requires it.



The database design will be kept flexible enough to support future expansion.



16\. Businesses and Branches



A business may have multiple branches.



Business

&#x20;  │

&#x20;  ├── Branch 1

&#x20;  ├── Branch 2

&#x20;  └── Branch 3



Branch-level information may eventually include:



Name

Location

Contact information

Operating hours

Staff

Inventory

Sales

Transactions

17\. Authentication



Authentication establishes the identity of a user.



The general process is:



User

&#x20;│

&#x20;▼

Login

&#x20;│

&#x20;▼

Laravel Authentication

&#x20;│

&#x20;├── Verify credentials

&#x20;├── Verify account status

&#x20;└── Establish authenticated session/token

&#x20;│

&#x20;▼

Authenticated User



Authentication will be centralized in the Laravel backend.



18\. API Authentication



The mobile application will communicate with the backend through authenticated API requests.



Conceptually:



Flutter App

&#x20;    │

&#x20;    │ Login

&#x20;    ▼

Laravel API

&#x20;    │

&#x20;    ▼

Authentication

&#x20;    │

&#x20;    ▼

Access Credential

&#x20;    │

&#x20;    ▼

Protected API Requests



The final API authentication implementation will use Laravel's supported authentication mechanisms rather than implementing a custom token system unnecessarily.



19\. Web Authentication



The administration application will also authenticate against the BizPay backend.



Web Browser

&#x20;    │

&#x20;    ▼

Admin Login

&#x20;    │

&#x20;    ▼

Laravel API

&#x20;    │

&#x20;    ▼

Authentication

&#x20;    │

&#x20;    ▼

Admin Dashboard



The backend remains responsible for determining whether the user is authorized to access administrative functionality.



20\. Authentication vs Authorization



These concepts will remain separate.



Authentication



Answers:



Who are you?



Authorization



Answers:



What are you allowed to do?



Example:



John logs in

&#x20;      │

&#x20;      ▼

Authentication

&#x20;      │

&#x20;      ▼

John is identified

&#x20;      │

&#x20;      ▼

Authorization

&#x20;      │

&#x20;      ▼

John has sales.create

&#x20;      │

&#x20;      ▼

Sale can be created

21\. Account Status



Users will eventually have account states such as:



Active

Inactive

Suspended

Pending



A suspended or inactive account must not be allowed to perform protected operations.



Account status will be checked by the backend.



22\. Password Security



Passwords must never be stored as plain text.



The backend will use Laravel's supported password hashing mechanisms.



The database will store a secure password hash rather than the original password.



23\. Sensitive Information



The following information must never be committed to GitHub:



Database passwords

API secrets

Payment provider credentials

Encryption keys

Laravel application keys

Access tokens

Private credentials



Environment variables will be used for secrets that belong to the server environment.



24\. Payment Credentials



Payment provider credentials are particularly sensitive.



Examples include credentials for:



M-Pesa

Airtel Money



These credentials must remain on the backend.



The mobile application must never contain payment-provider secret credentials.



Correct architecture:



Mobile App

&#x20;    │

&#x20;    ▼

BizPay API

&#x20;    │

&#x20;    ▼

Payment Service

&#x20;    │

&#x20;    ▼

M-Pesa / Airtel Money



Incorrect architecture:



Mobile App

&#x20;    │

&#x20;    └── Payment Provider Secret

25\. Authorization Flow



A protected request will conceptually follow this process:



1\. Client sends request

&#x20;         │

&#x20;         ▼

2\. Authentication

&#x20;         │

&#x20;         ▼

3\. Identify user

&#x20;         │

&#x20;         ▼

4\. Identify business

&#x20;         │

&#x20;         ▼

5\. Identify branch/context

&#x20;         │

&#x20;         ▼

6\. Check permission

&#x20;         │

&#x20;         ▼

7\. Execute operation

&#x20;         │

&#x20;         ▼

8\. Return response



If any required security condition fails, the backend rejects the request.



26\. Example Authorization



Suppose a cashier attempts to delete a product.



Request:



DELETE /api/v1/products/25



The backend checks:



Is the user authenticated?

&#x20;       │

&#x20;       YES

&#x20;       │

Does the product belong to the user's business?

&#x20;       │

&#x20;       YES

&#x20;       │

Does the user have products.delete?

&#x20;       │

&#x20;       NO

&#x20;       │

&#x20;       ▼

Access denied



The mobile or web application must not be trusted to enforce this restriction by itself.



27\. Platform-Level Authorization



Platform administrators operate outside normal business boundaries.



For example:



Platform Admin

&#x20;     │

&#x20;     ├── Business A

&#x20;     ├── Business B

&#x20;     ├── Business C

&#x20;     └── Platform Configuration



A business owner operates within:



Business A

&#x20;   │

&#x20;   ├── Branches

&#x20;   ├── Users

&#x20;   ├── Customers

&#x20;   ├── Products

&#x20;   └── Transactions



This distinction must be enforced by the backend.



28\. Admin Control Model



The administration platform will eventually provide interfaces for:



Platform Administration

│

├── Businesses

├── Users

├── Roles

├── Permissions

├── Features

├── Payment Providers

├── System Configuration

├── Audit Logs

└── Platform Monitoring



Business administration will provide:



Business Administration

│

├── Business Profile

├── Branches

├── Staff

├── Products

├── Customers

├── Inventory

├── Sales

├── Payments

└── Reports



Access to these areas will depend on permissions.



29\. Mobile Application Authorization



The Flutter application will obtain the authenticated user's permissions and configuration from the backend.



The mobile application may use this information to improve the user interface.



For example:



User does not have reports.view

&#x20;       │

&#x20;       ▼

Reports option is hidden



However, hiding the option is not a security mechanism.



The backend must still reject:



GET /api/v1/reports



if the user lacks the required permission.



30\. Web Application Authorization



The same principle applies to the web administration platform.



The interface may hide features that the user cannot access.



However:



UI restriction



is not sufficient.



The API must enforce the permission independently.



31\. Principle of Least Privilege



Users should receive only the permissions required to perform their responsibilities.



For example:



Cashier

&#x20;   ├── sales.view

&#x20;   ├── sales.create

&#x20;   ├── customers.view

&#x20;   └── customers.create



A cashier should not automatically receive:



users.delete

business.delete

payment-provider.update

32\. Auditability



Important administrative and financial actions should eventually be recorded in audit logs.



Examples:



User created

Role changed

Permission changed

Business configuration changed

Payment configuration changed

Refund processed

User suspended

Product deleted



Audit logs will help with:



Security

Troubleshooting

Accountability

Business operations

Compliance

Incident investigation

33\. Future Role Customization



BizPay will eventually allow administrators to create or modify roles through the administration interface.



Example:



Create Role



Name:

Inventory Manager



Permissions:

\[x] products.view

\[x] products.create

\[x] products.update

\[x] inventory.view

\[x] inventory.update

\[ ] payments.refund

\[ ] users.delete



This is an important part of the platform's no-hard-coding configuration philosophy.



34\. Security Boundary



The backend is the trusted security boundary.



&#x20;             UNTRUSTED CLIENTS

&#x20;       ┌──────────────────────────┐

&#x20;       │                          │

&#x20;       │       Mobile App         │

&#x20;       │                          │

&#x20;       │       Web Browser        │

&#x20;       │                          │

&#x20;       └────────────┬─────────────┘

&#x20;                    │

&#x20;                    ▼

&#x20;             ┌──────────────┐

&#x20;             │ BizPay API   │

&#x20;             │              │

&#x20;             │ Security     │

&#x20;             │ Validation   │

&#x20;             │ Authorization│

&#x20;             └──────┬───────┘

&#x20;                    │

&#x20;                    ▼

&#x20;              Trusted Server

&#x20;                    │

&#x20;                    ▼

&#x20;                 Database



Clients should be treated as potentially untrusted environments.



35\. API Versioning



Authentication and authorization will be designed around versioned APIs.



Initial API structure:



/api/v1/



Authentication endpoints may eventually include:



/api/v1/auth/login

/api/v1/auth/logout

/api/v1/auth/me



Protected endpoints will be grouped under the API version.



36\. Future Authentication Features



The architecture should allow future implementation of:



Password reset

Email verification

Phone verification

Two-factor authentication

Session management

Device management

Login history

Account recovery

Account suspension

Security notifications



These features will be added progressively.



37\. Authentication Architecture Summary



The intended relationship is:



&#x20;                   PLATFORM

&#x20;                      │

&#x20;                PLATFORM ADMIN

&#x20;                      │

&#x20;               ┌──────┴──────┐

&#x20;               │             │

&#x20;            Business      Business

&#x20;               │             │

&#x20;            Branches       Branches

&#x20;               │             │

&#x20;             Users          Users

&#x20;               │

&#x20;             Roles

&#x20;               │

&#x20;          Permissions

&#x20;               │

&#x20;         Protected API

&#x20;               │

&#x20;      ┌────────┴────────┐

&#x20;      │                 │

&#x20;   Web Admin         Mobile App

38\. Implementation Order



The authentication foundation will be implemented in the following order:



1\. Business model

&#x20;      ↓

2\. Branch model

&#x20;      ↓

3\. User/business relationship

&#x20;      ↓

4\. Roles

&#x20;      ↓

5\. Permissions

&#x20;      ↓

6\. Role-permission relationships

&#x20;      ↓

7\. User-role relationships

&#x20;      ↓

8\. Authentication

&#x20;      ↓

9\. Authorization middleware

&#x20;      ↓

10\. Business/tenant isolation

&#x20;      ↓

11\. API authentication

&#x20;      ↓

12\. Authentication testing



This order is intentional.



The multi-business foundation will be established before extensive authorization logic is written.



39\. Design Decision



BizPay will not use a single hard-coded administrator account as its long-term administration model.



Instead, the platform will implement:



Users

\+

Roles

\+

Permissions

\+

Business Context

\+

Platform Context



This provides a scalable foundation for future businesses and employees.



40\. Milestone Status



The architecture for authentication, authorization, and multi-business access has been defined.



Status:



AUTHENTICATION ARCHITECTURE: DEFINED

AUTHORIZATION ARCHITECTURE: DEFINED

MULTI-BUSINESS ARCHITECTURE: DEFINED

ROLE ARCHITECTURE: DEFINED

PERMISSION ARCHITECTURE: DEFINED



The next stage is implementation.



41\. Next Development Stage



The next implementation stage will create the database foundation for:



businesses

branches

roles

permissions

users



After the database foundation is complete, authentication and authorization will be implemented against it.



The resulting system will provide the foundation for the BizPay administration platform and mobile application.

