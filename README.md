# LingoStep - Language Learning Platform

A comprehensive language learning platform built with Symfony that offers interactive missions and quizzes to help users master new languages.

## 📋 Table of Contents

- Features
- Technologies
- Prerequisites
- Installation
- Database Setup
- Usage
- Project Structure
- API Endpoints
- Contributing
- License

## ✨ Features

- **Multi-language Support**: Learn different languages through structured courses
- **Interactive Missions**: Complete language learning missions with XP rewards
- **Quiz System**: Answer questions with multiple choice options
- **Progress Tracking**: Track your learning progress and XP accumulation
- **User Management**: User registration, authentication, and profile management
- **Responsive Design**: Modern, mobile-friendly interface

## 🛠 Technologies

- **Backend**: Symfony 7.x (PHP)
- **Database**: MySQL
- **ORM**: Doctrine
- **Frontend**: Twig templates, Bootstrap
- **Authentication**: Symfony Security
- **Forms**: Symfony Forms

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js (optional, for asset management)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd lingostep-back
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env .env.local
   ```
   
   Edit `.env.local` and configure your database connection:
   ```env
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/lingostep"
   ```

4. **Generate application secret**
   ```bash
   php bin/console secrets:generate-keys
   ```

## 🗄️ Database Setup

1. **Create the database**
   ```bash
   php bin/console doctrine:database:create
   ```

2. **Run migrations**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

3. **Load fixtures (optional)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

## 🎯 Usage

1. **Start the development server**
   ```bash
   symfony serve -d
   ```
   Or using PHP built-in server:
   ```bash
   php -S localhost:8000 -t public/
   ```

2. **Access the application**
   - Frontend: `http://localhost:8000`
   - Admin: `http://localhost:8000/admin` (if available)

3. **Create a user account**
   - Register at `/register`
   - Login at `/login`

4. **Start learning**
   - Browse available language courses
   - Select missions to complete
   - Answer quiz questions to earn XP

## 📁 Project Structure

```
lingostep-back/
├── config/              # Symfony configuration files
├── migrations/          # Database migrations
├── public/              # Web accessible files
├── src/
│   ├── Controller/      # Application controllers
│   │   ├── Admin/       # Admin-specific controllers
│   │   └── User/        # User-specific controllers
│   ├── Entity/          # Doctrine entities
│   ├── Form/            # Symfony form types
│   ├── Repository/      # Doctrine repositories
│   └── DataFixtures/    # Database fixtures
├── templates/           # Twig templates
│   ├── _components/     # Reusable components
│   ├── admin/           # Admin templates
│   └── mission_list/    # Mission listing templates
├── var/                 # Cache and logs
└── vendor/              # Dependencies
```

## 🏗️ Core Entities

- **User**: User accounts and authentication
- **LanguageCourse**: Available language courses
- **Mission**: Learning missions within courses
- **Question**: Quiz questions for missions
- **Option**: Multiple choice answers for questions
- **AnsweredQuestion**: User responses to questions
- **UserMission**: User progress on missions
- **UserLanguageCourse**: User enrollment in courses

## 🔗 Key Routes

- `/` - Homepage
- `/register` - User registration
- `/login` - User authentication
- `/language-course/{id}/missions` - Mission listing
- `/quiz/mission/{mission_id}/question/{question_id}` - Quiz interface
- `/quiz/mission/{mission_id}/result` - Mission results

## 🎮 Game Mechanics

- **XP System**: Earn experience points by completing missions
- **Success Threshold**: 70% correct answers required to pass a mission
- **One-time Rewards**: XP is awarded only on first successful completion

## 🐛 Common Issues

### Migration Errors
If you encounter foreign key constraint violations:
```bash
# Check for orphaned data
SELECT * FROM answered_question WHERE question_id NOT IN (SELECT id FROM question);

# Clean up orphaned records
UPDATE answered_question SET question_id = NULL WHERE question_id = 0;
```

### Template Errors
If missions without questions cause template errors:
- Ensure all missions have at least one question
- Or modify templates to handle empty question sets

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/new-feature`
3. Make your changes and commit: `git commit -am 'Add new feature'`
4. Push to the branch: `git push origin feature/new-feature`
5. Submit a pull request

