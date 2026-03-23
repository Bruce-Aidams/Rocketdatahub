# API Integration Guide

This document provides a comprehensive guide for connecting your CloudTech platform to external applications, and for allowing external applications to interact with CloudTech.

---

## 1. INBOUND: Connecting an External App to CloudTech

If you want an external mobile app, storefront, or website to interact with CloudTech (e.g., to fetch data bundles and place orders on behalf of users), use the following guide.

### Authentication

CloudTech uses Bearer Tokens (API Keys) for authentication. 

1. Log into your CloudTech dashboard.
2. Navigate to **API Access / API Keys**.
3. Generate a new key and save it securely.

Include the API key in the header of all your HTTP requests:
```http
Authorization: Bearer YOUR_API_KEY
Accept: application/json
Content-Type: application/json
```

### Available Endpoints

#### 1. Get Available Networks
Fetch a list of all active network providers (e.g., MTN, Vodafone, AirtelTigo).
- **Endpoint**: `GET /api/networks`
- **Response**:
```json
[
  "MTN",
  "AirtelTigo",
  "Telecel"
]
```

#### 2. Get Available Data Bundles (Products)
Fetch a list of all active data bundles and prices.
- **Endpoint**: `GET /api/products`
- **Response**: Array of bundle objects including `id`, `name`, `network`, `price`, etc.

#### 3. Place an Order
Purchase a data bundle for a specific phone number.
- **Endpoint**: `POST /api/orders`
- **Request Body**:
```json
{
  "bundle_id": 12,                  // The ID of the bundle from /api/products
  "recipient_phone": "0540000000",  // The target phone number
  "payment_method": "wallet"        // Use "wallet" to deduct from your balance
}
```
- **Response**:
```json
{
  "status": true,
  "message": "Order placed successfully",
  "redirect": "..."
}
```

#### 4. Place Bulk Orders
Purchase multiple bundles in a single request.
- **Endpoint**: `POST /api/orders/bulk`
- **Request Body**:
```json
{
  "payment_method": "wallet",
  "items": [
    {
      "bundle_id": 12,
      "recipient_phone": "0540000000"
    },
    {
      "bundle_id": 14,
      "recipient_phone": "0550000000"
    }
  ]
}
```

#### 5. Check Order Status
Retrieve the details and current status of a specific order.
- **Endpoint**: `GET /api/orders/{order_id}`
- **Response**: Returns the order object including the current `status` (e.g., `delivered`, `pending_payment`, `failed`).

---

## 2. OUTBOUND: Connecting CloudTech to an External Data/VTU Provider

If you want CloudTech to automatically fulfill orders by connecting to a third-party VTU or Data provider's API, follow these steps.

### Setup Steps
1. Log in to CloudTech as an **Admin**.
2. Navigate to **Admin > API Management > API Providers**.
3. Click **Add New Provider** and configure the connection details provided by your third-party API.

### Configuration Fields
- **Base URL**: The API endpoint where the order request will be sent (e.g., `https://api.vtuprovider.com/vend`).
- **Request Method**: Typically `POST`.
- **Headers**: Your authentication headers. Example:
  ```json
  {
    "Authorization": "Bearer YOUR_SECRET_PROVIDER_KEY",
    "Content-Type": "application/json"
  }
  ```
- **Request Body Template**: Instruct CloudTech how to format the data sent to the external API using placeholders.
  - `{{phone}}` -> Replaced with the customer's phone number.
  - `{{amount}}` -> Replaced with the data bundle plan.
  - `{{network}}` -> Replaced with the network name.
  - `{{request_id}}` -> Replaced with the unique order reference.
  
  *Example*:
  ```json
  {
    "network": "{{network}}",
    "mobile_number": "{{phone}}",
    "plan": "{{amount}}",
    "ref": "{{request_id}}"
  }
  ```
- **Response Settings**: Define how CloudTech knows the order was successful.
  - **Success Field**: The JSON key in the response that indicates status (e.g., `status`).
  - **Success Value**: The value that means success (e.g., `success`, `true`, or `200`).

Once configured, click **Test Connection** to ensure the third-party API is reachable, then toggle the provider to **Active**. CloudTech will now automatically route matching data orders through this provider.

---

## 3. Webhooks

CloudTech supports webhooks for real-time status updates:
- **Incoming Webhooks**: `POST /api/webhooks/incoming` - External providers can send status updates to this endpoint to transition pending CloudTech orders to `delivered` or `failed`.
- **Outgoing Webhooks**: Can be configured in the API integrations so that CloudTech automatically pings your external server when an order finishes processing.
