# REST API Specification — PrepareMe.com

## 1. Overview & Conventions

* **Base URL:** `http://localhost:8000/api/v1` (Production: `https://api.prepareme.com/api/v1`)
* **Protocol:** HTTPS, RESTful over HTTP/1.1 or HTTP/2
* **Content Negotiation:** All requests must include:
  ```http
  Accept: application/json
  Content-Type: application/json
  ```
  *(Except multipart/form-data for file uploads).*
* **Authentication:** Bearer token via Laravel Sanctum:
  ```http
  Authorization: Bearer 1|qXy...
  ```

---

## 2. Standard Response Envelopes

### 2.1 Single Item Success (200 OK / 201 Created)
```json
{
  "message": "Resource retrieved successfully.",
  "data": {
    "id": 1,
    "name": "বাংলা ভাষা ও সাহিত্য"
  }
}
```

### 2.2 Paginated Collection (200 OK)
```json
{
  "data": [
    { "id": 1, "question": "বাংলা লিপির উৎস কী?" }
  ],
  "links": {
    "first": "http://api.prepareme.com/api/v1/public-questions?page=1",
    "last": "http://api.prepareme.com/api/v1/public-questions?page=5",
    "prev": null,
    "next": "http://api.prepareme.com/api/v1/public-questions?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 95
  }
}
```

### 2.3 Validation Error (422 Unprocessable Content)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password field must be at least 8 characters."]
  }
}
```

---

## 3. Endpoint Reference

### 3.1 Authentication & Profile

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/auth/register` | Public | Register new candidate account |
| `POST` | `/auth/login` | Public | Authenticate and obtain Bearer token |
| `POST` | `/auth/logout` | User | Revoke current access token |
| `GET` | `/me` | User | Get authenticated user profile & role |
| `PUT` | `/profile` | User | Update personal information (name, phone, target exam) |
| `PUT` | `/profile/password` | User | Change user password |

#### Example: `POST /api/v1/auth/login`
**Request Body:**
```json
{
  "email": "user1@prepareme.com",
  "password": "Password123!"
}
```
**Response (200 OK):**
```json
{
  "message": "লগইন সফল হয়েছে।",
  "data": {
    "token": "2|XjKl9...",
    "user": {
      "id": 2,
      "name": "Rahim Ahmed",
      "email": "user1@prepareme.com",
      "role": "user",
      "target_exam": "47th BCS"
    }
  }
}
```

---

### 3.2 Public Curriculum & Learning

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/subjects` | Public | List all published subjects |
| `GET` | `/subjects/{slug}` | Public | Get subject details |
| `GET` | `/subjects/{subject}/topics` | Public | List topics under a subject |
| `GET` | `/topics/{topic}/study-guides` | Public | List study guides under a topic |
| `GET` | `/study-guides/{id}` | Public | Get full study guide with ordered sections |
| `GET` | `/public-questions` | Public | Search and filter public MCQ question bank |
| `GET` | `/public-questions/{id}` | Public | Get single public question with explanation |

#### Query Parameters for `/public-questions`:
* `subject_id` (integer, optional)
* `topic_id` (integer, optional)
* `difficulty` (`easy`, `medium`, `hard`)
* `search` (string, matches question stem or explanation)
* `page` (integer, default: 1)

---

### 3.3 Personal Study Notebook (Zero-IDOR)

*All personal notebook endpoints automatically scope to the authenticated user.*

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/my/questions` | User | List candidate's personal questions |
| `POST` | `/my/questions` | User | Create a new private question |
| `GET` | `/my/questions/{id}` | User | View personal question detail |
| `PUT` | `/my/questions/{id}` | User | Update question, answer, explanation, or tags |
| `DELETE` | `/my/questions/{id}` | User | Delete private question |
| `POST` | `/my/questions/{id}/archive` | User | Toggle active/archived state |

#### Example: `POST /api/v1/my/questions`
**Request Body:**
```json
{
  "question": "চর্যাপদের সবচেয়ে বেশি পদ কে রচনা করেছেন?",
  "answer": "কাহ্নপা (১৩টি পদ)।",
  "explanation": "চর্যাপদে মোট ২৪ জন কবির পদ পাওয়া গেছে, যার মধ্যে কাহ্নপা সর্বাধিক পদ রচনা করেছেন।",
  "source": "বাংলা সাহিত্যের ইতিহাস — মাহবুবুল আলম",
  "tags": ["বাংলা সাহিত্য", "চর্যাপদ", "বিসিএস"]
}
```

---

### 3.4 Bookmarks & Reading Progress

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/my/bookmarks` | User | List bookmarked study guides |
| `POST` | `/my/bookmarks` | User | Add guide to bookmarks (`study_guide_id`) |
| `DELETE` | `/my/bookmarks/{studyGuide}` | User | Remove guide from bookmarks |
| `GET` | `/my/progress` | User | List reading progress across guides |
| `PUT` | `/my/progress/{studyGuide}` | User | Mark guide progress (`status`: `started` / `completed`) |

---

### 3.5 OCR Digitization Studio

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/my/ocr-documents` | User | List candidate's uploaded textbook scans |
| `POST` | `/my/ocr-documents` | User | Upload image and dispatch OCR job |
| `GET` | `/my/ocr-documents/{id}` | User | Get scan status, processing state, and error info |
| `GET` | `/my/ocr-documents/{id}/result` | User | Get extracted OCR text and metrics |
| `POST` | `/my/ocr-documents/{id}/convert` | User | Request re-extraction with different language |
| `POST` | `/my/ocr-documents/{id}/save-as-question` | User | Convert extracted/edited text into notebook question |
| `DELETE` | `/my/ocr-documents/{id}` | User | Delete scan and associated OCR results |

#### Example: `POST /api/v1/my/ocr-documents`
* **Content-Type:** `multipart/form-data`
* **Fields:**
  * `image`: Binary file (JPEG, PNG, WebP; max 10MB)
  * `language`: `ben`, `eng`, or `ben+eng` (default: `ben+eng`)

**Response (201 Created):**
```json
{
  "message": "ছবিটি সফলভাবে আপলোড হয়েছে এবং প্রসেসিংয়ের জন্য কিউতে পাঠানো হয়েছে।",
  "data": {
    "id": 12,
    "original_filename": "bangla_grammar_page_42.jpg",
    "status": "pending",
    "language": "ben+eng",
    "created_at": "2026-09-13T09:30:00.000000Z"
  }
}
```

#### Example: `POST /api/v1/my/ocr-documents/{id}/save-as-question`
**Request Body:**
```json
{
  "question": "বাংলা উপসর্গ কয়টি ও কী কী?",
  "answer": "বাংলা উপসর্গ ২১টি। যেমন: অ, অঘা, অজ, অনা, আ, আর, আব, আন...",
  "explanation": "বাংলা একাডেমি প্রমিত ব্যাকরণ অনুযায়ী খাঁটি বাংলা উপসর্গ ২১টি।",
  "tags": ["বাংলা ব্যাকরণ", "উপসর্গ"]
}
```

---

### 3.6 Administrative Console (Admin Only)

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/admin/dashboard` | Admin | Aggregate statistics (users, guides, questions, OCR) |
| `GET` | `/admin/users` | Admin | Searchable user directory with role/status filters |
| `PUT` | `/admin/users/{id}/status` | Admin | Toggle user status (`active` / `suspended`) |
| `GET, POST` | `/admin/subjects` | Admin | Subject listing & creation |
| `PUT, DELETE`| `/admin/subjects/{id}` | Admin | Update or delete subject |
| `GET, POST` | `/admin/topics` | Admin | Topic listing & creation |
| `PUT, DELETE`| `/admin/topics/{id}` | Admin | Update or delete topic |
| `GET, POST` | `/admin/study-guides` | Admin | List & create study guides |
| `PUT, DELETE`| `/admin/study-guides/{id}` | Admin | Update or delete study guide |
| `POST` | `/admin/study-guides/{id}/publish` | Admin | Publish study guide |
| `POST` | `/admin/study-guides/{id}/archive` | Admin | Archive study guide |
| `GET, POST` | `/admin/public-questions` | Admin | List & create public MCQs |
| `PUT, DELETE`| `/admin/public-questions/{id}` | Admin | Update or delete public question |
| `POST` | `/admin/public-questions/{id}/publish` | Admin | Publish question |
| `POST` | `/admin/public-questions/{id}/archive` | Admin | Archive question |
| `GET` | `/admin/ocr-documents` | Admin | Audit all platform OCR extraction jobs |
| `GET` | `/admin/ocr-documents/{id}` | Admin | Inspect document metadata and raw OCR traces |
