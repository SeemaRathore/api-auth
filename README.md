# API Endpoints

## Register
- **Method**: POST
- **URL**: /api/register
- **Body**:
    - `name` (string, required)
    - `email` (string, required, valid email)
    - `password` (string, required, min: 8)

## Login
- **Method**: POST
- **URL**: /api/login
- **Body**:
    - `email` (string, required, valid email)
    - `password` (string, required)

## Logout
- **Method**: POST
- **URL**: /api/logout
- **Headers**:
    - `Authorization`: Bearer <token>

## User Profile
- **Method**: GET
- **URL**: /api/user-profile
- **Headers**:
    - `Authorization`: Bearer <token>
