# Add New Product — API & Feature Reference

When a search returns no products, the user can submit a new product. Submissions go into a
**review table** (`wp_product_requests`), not the live catalog. An admin reviews them in
wp-admin and approves (creates a draft WooCommerce product) or rejects.

## Endpoint (Android / iOS / Web)

```
POST /wp-json/products/v2/request-add
```

**Auth:** Login required.
- App: send the JWT in the `Authorization: Bearer <token>` header (same as other `products/v2` calls).
- Web: cookie session + `X-WP-Nonce` header (handled automatically by the website modal).

**Headers**
```
Content-Type: application/json
Authorization: Bearer <jwt>      // app
```

**Body**

| Field                 | Required | Notes                                            |
|-----------------------|----------|--------------------------------------------------|
| `product_name`        | yes      | Product title                                    |
| `product_description` | yes      | Description                                       |
| `product_price`       | no       | String/number, e.g. `"39.99"`                    |
| `product_image`       | no       | Base64 image (data-URI prefix optional), <5 MB   |
| `keyword`             | no       | What the user searched for (for admin context)   |
| `source`              | no       | `app` (default) or `web`                         |

**Example**
```json
{
  "product_name": "Jack Daniels",
  "product_description": "Tennessee whiskey, 70cl",
  "product_price": "29.99",
  "product_image": "data:image/jpeg;base64,/9j/4AAQSk...",
  "keyword": "jack daniels"
}
```

## Responses

**200 — success** (show the "Voila!" screen)
```json
{ "status": "success", "message": "Your product Jack Daniels has been added", "request_id": 42 }
```

**400 — missing required fields**
```json
{ "status": "error", "message": "Product name and description are required." }
```

**409 — duplicate** (already a live product, or already pending review)
```json
{ "status": "error", "message": "A product with this name already exists." }
```
```json
{ "status": "error", "message": "This product has already been submitted and is awaiting review." }
```

**401 — not logged in**
```json
{ "code": "rest_forbidden", "message": "You must be logged in to submit a product." }
```

### Client handling
- `status === "success"` → show success screen using `message`.
- `409` / `400` → show `message` inline.
- `401` → prompt login.

## Admin review panel

wp-admin → **Product Requests** (pending count badge in the menu).
Filter by Pending / Approved / Rejected / All. Each pending row has:
- **Approve** → creates a **draft** WooCommerce product (name, description, price, image as featured image). Admin then reviews and publishes it from the Products screen.
- **Reject** → marks the request rejected; nothing is added to the catalog.

## Data model — `wp_product_requests`

`id, product_name, product_description, product_price, product_image, keyword,
submitted_by, source, status (pending|approved|rejected), created_product_id,
reviewed_by, reviewed_at, created`

The table is created automatically on load (no plugin reactivation needed).
