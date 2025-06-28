# Nasser Al-Khamees Academy Configuration

## Environment Configuration (.env)

Copy the following to your `.env` file:

```env
APP_NAME="مسجد ناصر بن علي الخميس"
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_TIMEZONE=Asia/Riyadh
APP_URL=http://localhost

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
APP_FAKER_LOCALE=ar_SA

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nasser_academy
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@nasser-academy.com"
MAIL_FROM_NAME="${APP_NAME}"

# Academy Specific Settings
ACADEMY_TIMEZONE="Asia/Riyadh"
ACADEMY_COUNTRY="Saudi Arabia"
ACADEMY_DEFAULT_PHONE_CODE="966"
ACADEMY_SUPPORT_EMAIL="support@nasser-academy.com"
ACADEMY_CONTACT_PHONE="966509763628"
```

## Setup Instructions

1. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Update .env with the above configuration**

3. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

4. **Run migrations and seed academy data:**
   ```bash
   php artisan academy:seed-nasser --fresh
   ```

5. **Access the application:**
   - URL: http://localhost (or your configured URL)
   - Login as any teacher with `password123`
   - Example: `anas.suleiman@nasser-academy.com`

## Academy Features Configured

✅ **Academy Identity**
- Name: مسجد ناصر بن علي الخميس (Nasser Al-Khamees Mosque)
- Department: Z11 مجمع إ (Digital Itqan Complex)
- Locale: Arabic (ar) with English fallback

✅ **Study Circles (10 total)**
- حلقة أشبال القرآن (Ages 5-8) - 2 circles
- حلقة المربيين (Ages 9-12) - 4 circles  
- حلقة المعلمين (Ages 11-18) - 3 circles
- حلقة القدوات (Ages 18+) - 1 circle

✅ **Teachers (9 total)**
- Saudi Arabian teachers: 6
- Egyptian teachers: 1
- Syrian teachers: 2

✅ **Countries Supported**
- Saudi Arabia, Egypt, Syria, Bahrain, Jordan, Oman, Yemen, Estonia

✅ **Translations**
- Comprehensive Arabic/English translations
- Academy-specific terms and circle names
- Teacher names and descriptions

## Default Login Credentials

All users created with:
- **Password:** `password123`
- **Email format:** `name@nasser-academy.com`

### Sample Teacher Logins:
- `anas.suleiman@nasser-academy.com` (انس سليمان عيسى)
- `mohamed.anwar@nasser-academy.com` (محمد أنور عبد الباقي السيد)
- `osama.tamimi@nasser-academy.com` (أسامة بن سعود التميمي)

## Database Structure

The academy seeder creates:
1. **Department:** مسجد ناصر بن علي الخميس - Z11 مجمع إ
2. **Users:** Teachers and students with proper roles
3. **Study Circles:** 10 circles with Arabic names from CSV
4. **Circle Students:** Enrollment relationships
5. **Translations:** Academy-specific Arabic/English translations

## Time Zones & Scheduling

- **Default Timezone:** Asia/Riyadh (Saudi Arabia)
- **Circle Times:** 
  - فترة العصر (Asr period)
  - فترة المغرب (Maghrib period)

## Support

For technical support or questions about this academy configuration:
- Email: support@nasser-academy.com
- Phone: 966509763628 (Teacher Anas Suleiman)

---

**Academy Branch:** `academy-nasser-alkhamees-data`  
**Configuration Date:** January 2025  
**Ready for Production:** ✅ 