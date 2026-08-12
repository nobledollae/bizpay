\# BizPay System Architecture



\*\*Project:\*\* BizPay

\*\*Document:\*\* System Architecture

\*\*Version:\*\* 0.1.0

\*\*Status:\*\* Initial Architecture Draft

\*\*Last Updated:\*\* 12 August 2026



\---



\# 1. Introduction



This document describes the technical architecture of BizPay.



It defines:



\* Major system components

\* Communication patterns

\* Security boundaries

\* Multi-tenant design

\* Payment integration approach

\* Configuration architecture

\* Scalability considerations



This document serves as the technical blueprint for implementation.



\---



\# 2. Architectural Principles



BizPay will follow these principles:



1\. Separation of concerns

2\. Multi-tenant data isolation

3\. Configuration-driven behavior

4\. Secure payment processing

5\. API-first design

6\. Role-based access control

7\. Scalability

8\. Maintainability

9\. Extensibility

10\. Comprehensive documentation



\---



\# 3. High-Level Architecture



```text id="cw37lu"

&#x20;                   BIZPAY PLATFORM



&#x20;       ┌───────────────────────────────────┐

&#x20;       │                                   │

&#x20;       │      WEB MANAGEMENT SYSTEM        │

&#x20;       │         (Laravel Web)             │

&#x20;       │                                   │

&#x20;       └────────────────┬──────────────────┘

&#x20;                        │

&#x20;                        │ HTTPS / REST

&#x20;                        │

&#x20;       ┌────────────────▼──────────────────┐

&#x20;       │                                   │

&#x20;       │         LARAVEL BACKEND           │

&#x20;       │                                   │

&#x20;       │ Authentication                    │

&#x20;       │ Authorization                     │

&#x20;       │ Configuration Engine              │

&#x20;       │ Payment Engine                    │

&#x20;       │ Business Logic                    │

&#x20;       │ Reporting                         │

&#x20;       │ Audit Logging                     │

&#x20;       │                                   │

&#x20;       └───────────────┬───────────────────┘

&#x20;                       │

&#x20;       ┌───────────────┼───────────────────┐

&#x20;       │               │                   │

&#x20;       ▼               ▼                   ▼

&#x20;    MySQL         M-Pesa API         Airtel API

&#x20;       ▲

&#x20;       │

&#x20;       │ HTTPS / REST

&#x20;       │

┌───────┴────────┐

│                │

│ Flutter Mobile │

│                │

└────────────────┘

```



\---



\# 4. Core Components



BizPay consists of:



\## 4.1 Web Management Platform



Purpose:



\* Administration

\* Configuration

\* Reporting

\* User management

\* Business management



Technology:



\* Laravel

\* Blade (initially)

\* Tailwind CSS (later if required)



Users:



\* System Administrators

\* Business Owners

\* Managers

\* Accountants



\---



\## 4.2 Mobile Application



Technology:



\* Flutter



Purpose:



\* Sales

\* Customers

\* Inventory

\* Payments

\* Receipts



Users:



\* Cashiers

\* Managers

\* Business Owners



The mobile application retrieves data and configuration through APIs.



\---



\## 4.3 Backend API



Technology:



\* Laravel API



Responsibilities:



\* Authentication

\* Authorization

\* Business logic

\* Payment processing

\* Configuration delivery

\* Reporting

\* Notifications

\* Audit logging



The API becomes the central system component.



\---



\# 5. Multi-Tenant Architecture



BizPay supports multiple businesses.



Example:



```text id="x36ncl"

BizPay

&#x20;  │

&#x20;  ├── Business A

&#x20;  │      ├── Users

&#x20;  │      ├── Products

&#x20;  │      ├── Customers

&#x20;  │      ├── Sales

&#x20;  │      └── Payments

&#x20;  │

&#x20;  └── Business B

&#x20;         ├── Users

&#x20;         ├── Products

&#x20;         ├── Customers

&#x20;         ├── Sales

&#x20;         └── Payments

```



Every business-owned record shall contain:



```text id="b9l4dw"

business\_id

```



Examples:



\* customers

\* products

\* sales

\* payments

\* inventory

\* branches

\* users



\---



\# 6. Branch Architecture



Businesses may contain multiple branches.



Example:



```text id="g62bga"

Business

&#x20;   │

&#x20;   ├── Branch A

&#x20;   │      ├── Users

&#x20;   │      ├── Sales

&#x20;   │      └── Inventory

&#x20;   │

&#x20;   └── Branch B

```



Branch-aware tables may include:



```text id="9wfftx"

branch\_id

```



\---



\# 7. User Architecture



Hierarchy:



```text id="rbuw1z"

System Admin

&#x20;     │

Business Owner

&#x20;     │

Manager

&#x20;     │

Cashier

```



Permissions control actions.



Examples:



```text id="g50slw"

Create Customer

Edit Product

Create Sale

Initiate Payment

View Reports

Manage Staff

```



Role-Based Access Control (RBAC) will be used.



\---



\# 8. Authentication Architecture



Authentication will use:



\* Laravel Sanctum



Flow:



```text id="0cx0sj"

User

&#x20;  ↓

Login

&#x20;  ↓

Laravel

&#x20;  ↓

Token

&#x20;  ↓

API Access

```



Supported:



\* Login

\* Logout

\* Password reset

\* Account activation

\* Session management



\---



\# 9. Authorization Architecture



Authentication:



```text id="l68aef"

Who are you?

```



Authorization:



```text id="g8jefg"

What are you allowed to do?

```



Permissions:



```text id="vtn16f"

view\_customers

create\_sales

process\_payments

manage\_inventory

view\_reports

```



\---



\# 10. Configuration Engine



A major BizPay component.



Purpose:



Allow supported changes without modifying source code.



Configurable items:



\* Business name

\* Logo

\* Theme

\* Enabled modules

\* Payment methods

\* Receipt settings

\* Dashboard settings



Example:



```text id="v1pcw0"

Inventory: ENABLED

Loyalty: DISABLED

Expenses: DISABLED

```



\---



\# 11. Configuration Hierarchy



```text id="8gx9v2"

System Defaults

&#x20;      ↓

Business Settings

&#x20;      ↓

Branch Settings

&#x20;      ↓

User Permissions

```



More specific rules override broader rules.



\---



\# 12. Payment Architecture



BizPay uses a provider abstraction layer.



```text id="gtg5az"

Payment Service

&#x20;       │

&#x20;       ├── M-Pesa Provider

&#x20;       │

&#x20;       └── Airtel Provider

```



The sales module does not communicate directly with payment providers.



\---



\# 13. Payment Flow



Example:



```text id="y5hxbv"

Cashier

&#x20;  ↓

Enter Phone Number

&#x20;  ↓

Select Provider

&#x20;  ↓

Laravel API

&#x20;  ↓

Payment Service

&#x20;  ↓

Provider API

&#x20;  ↓

Customer Receives Prompt

&#x20;  ↓

Customer Pays

&#x20;  ↓

Provider Callback

&#x20;  ↓

Laravel Verification

&#x20;  ↓

Database

&#x20;  ↓

Mobile App Updated

```



\---



\# 14. Security Boundary



Mobile application:



```text id="8ufjjl"

NO payment credentials

```



Credentials remain:



```text id="2g7r2o"

Laravel Backend

```



This protects:



\* API keys

\* Secrets

\* Credentials



\---



\# 15. Payment Callback Architecture



```text id="7dsp17"

Provider

&#x20;     ↓

Callback URL

&#x20;     ↓

Laravel

&#x20;     ↓

Verification

&#x20;     ↓

Database Update

&#x20;     ↓

User Notification

```



\---



\# 16. API Architecture



Example endpoints:



```text id="0yjnt2"

POST /api/login



GET /api/mobile/configuration



GET /api/customers



POST /api/sales



POST /api/payments/request



GET /api/reports

```



REST principles will be used.



\---



\# 17. Notification Architecture



Future channels:



\* SMS

\* Email

\* Push Notifications

\* WhatsApp



Notification service:



```text id="nyk3p4"

System

&#x20;  ↓

Notification Service

&#x20;  ↓

Channel

```



\---



\# 18. Audit Architecture



Example:



```text id="3h5ih0"

User

Action

Target

Timestamp

Result

```



Examples:



\* Login

\* Payment

\* Sale

\* Permission change

\* Configuration update



\---



\# 19. Reporting Architecture



Reports use:



```text id="jtrw8h"

Sales

Payments

Inventory

Customers

```



Reports respect:



\* business\_id

\* branch\_id

\* permissions



\---



\# 20. Database Architecture



Initial major tables:



```text id="lh9j5d"

businesses

branches

users

roles

permissions

role\_permissions

customers

categories

products

inventory

sales

sale\_items

payments

configurations

audit\_logs

```



Additional tables will be added as needed.



\---



\# 21. Deployment Architecture



Development:



```text id="qjlwm5"

Laravel

MySQL

Flutter

GitHub

```



Future Production:



```text id="5n7uh4"

Nginx

PHP

MySQL

Redis

HTTPS

Cloud Storage

```



\---



\# 22. Scalability



Future support:



\* Multiple businesses

\* Multiple branches

\* More payment providers

\* More modules

\* More users

\* More reports



\---



\# 23. Future Integrations



Potential integrations:



\* Banks

\* Card payments

\* WhatsApp

\* SMS providers

\* Accounting systems

\* E-commerce systems



\---



\# 24. Architecture Decisions



Decision 1:



Flutter never connects directly to MySQL.



Decision 2:



Payment credentials remain on the backend.



Decision 3:



The web platform controls configuration.



Decision 4:



Multi-tenant data isolation is mandatory.



Decision 5:



Payment providers are abstracted.



Decision 6:



Configuration determines behavior where supported.



\---



\# 25. Initial Development Order



```text id="mbr4z0"

Requirements

&#x20;     ↓

Architecture

&#x20;     ↓

Database

&#x20;     ↓

Laravel Backend

&#x20;     ↓

Authentication

&#x20;     ↓

Administration

&#x20;     ↓

Customers

&#x20;     ↓

Products

&#x20;     ↓

Inventory

&#x20;     ↓

Sales

&#x20;     ↓

Payments

&#x20;     ↓

Flutter

```



\---



\# 26. Current Status



Completed:



\* Project setup

\* Documentation

\* Requirements

\* Architecture



Next:



\* Database Design

\* Laravel Backend Initialization



