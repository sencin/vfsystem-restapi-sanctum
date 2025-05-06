# 📘 API Documentation

Welcome to the API documentation for your automated farming backend. Below you'll find grouped and well-structured endpoints with descriptions and controller mappings.

---

<details>
<summary>📡 <strong>Tower Endpoints</strong></summary>

| Method | Endpoint               | Description         | Controller                        |
|--------|------------------------|---------------------|-----------------------------------|
| GET    | `/api/availabletowers` | List all towers     | `Api\TowerController@showAllTowers` |
| GET    | `/api/getTowerId`      | Get tower ID        | `Api\ReadingController@getTowerId`   |

</details>

---

<details>
<summary>📊 <strong>Reading Endpoints</strong></summary>

| Method     | Endpoint                | Description               | Controller                            |
|------------|-------------------------|---------------------------|---------------------------------------|
| GET        | `/api/readings`         | List all readings         | `Api\ReadingController@index`         |
| POST       | `/api/readings`         | Store a new reading       | `Api\ReadingController@store`         |
| GET        | `/api/readings/{id}`    | Get specific reading      | `Api\ReadingController@show`          |
| PUT/PATCH  | `/api/readings/{id}`    | Update specific reading   | `Api\ReadingController@update`        |
| DELETE     | `/api/readings/{id}`    | Delete a reading          | `Api\ReadingController@destroy`       |
| GET        | `/api/average_readings` | Get average sensor data   | `Api\ReadingController@getAverageReadings` |

</details>

---

<details>
<summary>🌿 <strong>Plant Information</strong></summary>

| Method     | Endpoint                          | Description               | Controller                                  |
|------------|-----------------------------------|---------------------------|---------------------------------------------|
| GET        | `/api/plantinformations`          | List plant info           | `Api\PlantInformationController@index`      |
| POST       | `/api/plantinformations`          | Create plant info         | `Api\PlantInformationController@store`      |
| GET        | `/api/plantinformations/{id}`     | View plant info           | `Api\PlantInformationController@show`       |
| PUT/PATCH  | `/api/plantinformations/{id}`     | Update plant info         | `Api\PlantInformationController@update`     |
| DELETE     | `/api/plantinformations/{id}`     | Delete plant info         | `Api\PlantInformationController@destroy`    |

</details>

---

<details>
<summary>🧪 <strong>Plant Requirements</strong></summary>

| Method     | Endpoint                          | Description                  | Controller                                 |
|------------|-----------------------------------|------------------------------|--------------------------------------------|
| GET        | `/api/plantrequirements`          | List requirements            | `Api\PlantRequirementController@index`     |
| POST       | `/api/plantrequirements`          | Create requirement           | `Api\PlantRequirementController@store`     |
| GET        | `/api/plantrequirements/{id}`     | View requirement             | `Api\PlantRequirementController@show`      |
| PUT/PATCH  | `/api/plantrequirements/{id}`     | Update requirement           | `Api\PlantRequirementController@update`    |
| DELETE     | `/api/plantrequirements/{id}`     | Delete requirement           | `Api\PlantRequirementController@destroy`   |

</details>

---

<details>
<summary>🔁 <strong>Plant Transplants</strong></summary>

| Method     | Endpoint                           | Description              | Controller                                  |
|------------|------------------------------------|--------------------------|---------------------------------------------|
| GET        | `/api/planttransplants`            | List transplants         | `Web\PlantTransplantController@index`       |
| POST       | `/api/planttransplants`            | Create transplant record | `Web\PlantTransplantController@store`       |
| GET        | `/api/planttransplants/{id}`       | View transplant          | `Web\PlantTransplantController@show`        |
| PUT/PATCH  | `/api/planttransplants/{id}`       | Update transplant        | `Web\PlantTransplantController@update`      |
| DELETE     | `/api/planttransplants/{id}`       | Delete transplant        | `Web\PlantTransplantController@destroy`     |

</details>

---

<details>
<summary>🔐 <strong>Authentication</strong></summary>

| Method | Endpoint        | Description       | Controller              |
|--------|-----------------|-------------------|-------------------------|
| POST   | `/api/login`    | User login        | `Web\UserAuth@loginToken` |
| POST   | `/api/logout`   | User logout       | `Web\UserAuth@logout`     |
| POST   | `/api/register` | Register new user | `Web\UserAuth@register`   |

</details>

---

<details>
<summary>👤 <strong>User Management</strong></summary>

| Method | Endpoint            | Description        | Controller                              |
|--------|---------------------|--------------------|------------------------------------------|
| GET    | `/api/pendingusers` | List pending users | `Web\UserController@getPendingUsersAPI`  |

</details>

---

<details>
<summary>📟 <strong>Sensor Endpoints</strong></summary>

| Method | Endpoint       | Description      | Controller                          |
|--------|----------------|------------------|-------------------------------------|
| GET    | `/api/sensors` | List all sensors | `Api\SensorController@index`        |

</details>

---

📅 **Last Updated:** May 2025  
📍 **Maintainer:** `ICT - University of Eastern Philippines`  
📦 **Version:** `v1.0.0`

