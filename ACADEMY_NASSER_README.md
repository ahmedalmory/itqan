# مسجد ناصر بن علي الخميس - Nasser Al-Khamees Academy

## 🎉 Complete Academy Customization

This branch contains a **complete transformation** of the application for **مسجد ناصر بن علي الخميس** (Nasser Al-Khamees Academy) based on your provided CSV data.

### ✅ **Major Changes Made**

#### 🏷️ **App Identity Updated**
- ✅ **App Name Changed**: From "Itqan Academy" to "مسجد ناصر بن علي الخميس"
- ✅ **Configuration Updated**: `config/app.php` now reflects academy name
- ✅ **Comprehensive Translations**: New academy-specific Arabic/English translations
- ✅ **Branding Updated**: Welcome messages and feature descriptions customized

#### 📊 **Academy Data Structure**
- **Academy Department**: Z11 مجمع إ (Digital Itqan Complex)
- **9 Teachers** with real contact information from the CSV
- **10 Study Circles** with authentic Arabic names and schedules
- **100+ Students** enrolled in various circles based on CSV data
- **8 Countries**: Saudi Arabia, Egypt, Syria, Bahrain, Jordan, Oman, Yemen, Estonia

#### 🆕 **New Files Created**
- `database/seeders/Translations/NasserAcademyTranslationSeeder.php` - Academy-specific translations
- `NASSER_ACADEMY_CONFIG.md` - Complete setup and configuration guide
- Enhanced `SeedNasserAcademy` command with translation support

## Quick Start

### Option 1: Fresh Installation (Recommended)
```bash
# Run with fresh migration and seeding
php artisan academy:seed-nasser --fresh
```

### Option 2: Add to Existing Database
```bash
# Seed only the academy data (requires existing countries/languages)
php artisan db:seed --class=NasserAlKhameesAcademySeeder
```

### Option 3: Seed Only Countries First
```bash
# If you only want to add the required countries and languages
php artisan academy:seed-nasser --countries-only
```

## Study Circles Structure

| Circle Name | Teacher | Age Group | Time | Max Students |
|-------------|---------|-----------|------|--------------|
| حلقة أشبال القرآن (المربي أنس سليمان) | انس سليمان عيسى | 5-8 سنوات | فترة العصر | 25 |
| حلقة أشبال القرآن (المربي محمد أنور) | محمد أنور عبد الباقي السيد | 5-8 سنوات | فترة العصر | 25 |
| حلقة القدوات | أسامة بن سعود التميمي | 18-60 سنة | فترة المغرب | 30 |
| حلقة المربي حذيفة المعيوف | حذيفة بن احمد المعيوف | 9-12 سنة | فترة العصر | 20 |
| حلقة المربي عصام سعد | عصام سعد أحمد سعد | 9-12 سنة | فترة العصر | 20 |
| حلقة المربي علي سليمان | علي بن سليمان | 9-12 سنة | فترة العصر | 20 |
| حلقة المربي محمد الزيني | محمد الزيني | 9-12 سنة | فترة المغرب | 20 |
| حلقة المعلم أحمد محمد | أحمد محمد موسى | 11-14 سنة | فترة المغرب | 20 |
| حلقة المعلم علي سليمان (متوسط) | علي بن سليمان | 14-17 سنة | فترة المغرب | 20 |
| حلقة المعلم محمد أبو زاهر | محمد ابو زاهر موسى | 15-18 سنة | فترة المغرب | 20 |

## Teachers Information

### Saudi Arabian Teachers
- **انس سليمان عيسى** - Phone: 966509763628
- **أسامة بن سعود التميمي** - Phone: 966540440554
- **حذيفة بن احمد المعيوف** - Phone: 966594378842
- **عصام سعد أحمد سعد** - Phone: 966567300247
- **علي بن سليمان** - Phone: 966548984174
- **محمد الزيني** - Phone: 966539404929

### Egyptian Teachers
- **محمد أنور عبد الباقي السيد** - Phone: 20545139544

### Syrian Teachers
- **أحمد محمد موسى** - Phone: 963551083678
- **محمد ابو زاهر موسى** - Phone: 963504499864

## Student Data

The seeder includes actual student data from the provided CSV with:
- Real Arabic names
- Correct ages and contact information
- Proper assignment to study circles
- Support for multiple nationalities

## Default Credentials

All users (teachers and students) are created with:
- **Password**: `password123`
- **Email format**: `name@nasser-academy.com`

## Database Schema

The seeder creates:
1. **Department**: مسجد ناصر بن علي الخميس - Z11 مجمع إ
2. **Users**: Teachers and students with their roles
3. **Study Circles**: 10 circles with proper teacher assignments
4. **Circle Students**: Enrollment relationships between students and circles

## Files Modified/Created

- `database/seeders/NasserAlKhameesAcademySeeder.php` - Main seeder
- `app/Console/Commands/SeedNasserAcademy.php` - Custom artisan command
- `database/seeders/CountrySeeder.php` - Added Estonia
- `database/seeders/DatabaseSeeder.php` - Added academy seeder

## Usage Examples

### Running the Academy Seeder
```bash
# Fresh database with academy data
php artisan academy:seed-nasser --fresh

# Check the results
php artisan tinker
>>> App\Models\Department::where('name', 'like', '%ناصر%')->first()
>>> App\Models\User::where('role', 'teacher')->count()
>>> App\Models\StudyCircle::count()
```

### Accessing the Application
After seeding, you can login as any teacher using:
- Email: `anas.suleiman@nasser-academy.com`
- Password: `password123`

## Support

This customization is based on real data from مسجد ناصر بن علي الخميس academy. All teacher and student information has been preserved as provided in the original CSV data.

---

**Created for**: مسجد ناصر بن علي الخميس  
**Date**: January 2025  
**Branch**: `academy-nasser-alkhamees-data` 