# Tours System Documentation

## Overview

The Tours System is a comprehensive multi-language tour management solution for the CMS. It provides complete functionality for managing tours, daily itineraries, and photo galleries with support for English and Spanish languages.

## Database Schema

### Tours Table (`tours`)

Main table storing tour information with multi-language support.

**Key Fields:**
- `id` - Primary key
- `name` - Tour name/identifier
- `slug` - SEO-friendly URL slug
- `language` - Language code (en, es)
- `translation_group` - Links related translations
- `title` - Display title
- `subtitle` - Short subtitle
- `short_description` - Brief description
- `description` - Full tour description
- `itinerary` - Overview itinerary text
- `image` - Main tour image
- `cover_image` - Cover/hero image
- `highlights` - JSON array of tour highlights
- `price` - Tour price (decimal)
- `price_includes` - JSON array of inclusions
- `price_excludes` - JSON array of exclusions
- `duration_days` - Tour duration in days
- `max_participants` - Maximum group size
- `difficulty_level` - easy, moderate, challenging, extreme
- `category` - Tour category
- `location` - Tour location
- `status` - active, inactive, draft
- `featured` - Featured tour flag
- `sort_order` - Display order
- `meta_title`, `meta_description`, `meta_keywords` - SEO fields

### Tour Details Table (`tour_details`)

Daily itinerary information for each tour.

**Key Fields:**
- `id` - Primary key
- `tour_id` - Foreign key to tours table
- `day` - Day number (1, 2, 3, etc.)
- `title` - Day title
- `description` - Day description
- `activities` - JSON array of activities
- `meals` - Meal information (B=Breakfast, L=Lunch, D=Dinner)
- `accommodation` - Accommodation details
- `transport` - Transportation information
- `notes` - Additional notes
- `sort_order` - Display order

### Tour Photos Table (`tour_photos`)

Photo gallery for tours with categorization.

**Key Fields:**
- `id` - Primary key
- `tour_id` - Foreign key to tours table
- `image` - Image file path
- `title` - Photo title
- `description` - Photo description
- `alt_text` - Alt text for accessibility
- `type` - gallery, itinerary, accommodation, activity
- `day` - Associated day (for itinerary photos)
- `sort_order` - Display order
- `is_featured` - Featured photo flag

## Models

### Tour Model (`app/Models/Tour.php`)

**Key Methods:**

#### Basic CRUD Operations
```php
// Get all tours with filters
$tours = $tourModel->getAll(['language' => 'en', 'status' => 'active']);

// Get tour by ID
$tour = $tourModel->getById($id);

// Get tour by slug and language
$tour = $tourModel->getBySlug('machu-picchu-adventure', 'en');

// Create new tour
$tourId = $tourModel->create($tourData);

// Update tour
$tourModel->update($id, $updateData);

// Delete tour
$tourModel->delete($id);
```

#### Specialized Queries
```php
// Get featured tours
$featured = $tourModel->getFeatured('en', 6);

// Get tours by category
$categoryTours = $tourModel->getByCategory('Cultural', 'en');

// Get tour translations
$translations = $tourModel->getTranslations($translationGroup);

// Search tours
$results = $tourModel->search('Machu Picchu', 'en', ['category' => 'Cultural']);
```

#### Tour Details and Photos
```php
// Get full tour with details and photos
$fullTour = $tourModel->getFullTour($id);

// Get tour details (daily itinerary)
$details = $tourModel->getDetails($tourId);

// Get tour photos
$photos = $tourModel->getPhotos($tourId, 'gallery');
```

#### Utility Methods
```php
// Generate unique slug
$slug = $tourModel->generateSlug('Amazing Tour Title', 'en');

// Get available categories
$categories = $tourModel->getCategories('en');

// Get tour statistics
$stats = $tourModel->getStats();

// Duplicate tour
$newTourId = $tourModel->duplicate($id, 'es');
```

### TourDetail Model (`app/Models/TourDetail.php`)

**Key Methods:**

```php
// Get all details for a tour
$details = $tourDetailModel->getByTourId($tourId);

// Get specific day details
$dayDetail = $tourDetailModel->getByTourAndDay($tourId, 3);

// Create new day detail
$detailId = $tourDetailModel->create([
    'tour_id' => $tourId,
    'day' => 1,
    'title' => 'Arrival Day',
    'description' => 'Welcome to Peru...',
    'activities' => ['Airport transfer', 'City tour'],
    'meals' => 'D',
    'accommodation' => 'Hotel in Lima'
]);

// Get day with associated photos
$dayWithPhotos = $tourDetailModel->getDayWithPhotos($tourId, 2);

// Get tour summary (all days overview)
$summary = $tourDetailModel->getTourSummary($tourId);

// Duplicate details for another tour
$tourDetailModel->duplicateForTour($sourceTourId, $targetTourId);
```

### TourPhoto Model (`app/Models/TourPhoto.php`)

**Key Methods:**

```php
// Get all photos for a tour
$photos = $tourPhotoModel->getByTourId($tourId);

// Get gallery photos only
$gallery = $tourPhotoModel->getGallery($tourId, 12);

// Get featured photos
$featured = $tourPhotoModel->getFeatured($tourId);

// Get photos by type
$itineraryPhotos = $tourPhotoModel->getByType('itinerary', $tourId);

// Upload and create photo
$photoId = $tourPhotoModel->uploadAndCreate($tourId, $_FILES['photo'], [
    'title' => 'Beautiful Landscape',
    'type' => 'gallery',
    'is_featured' => 1
]);

// Set featured photo
$tourPhotoModel->setFeatured($photoId, $tourId);

// Reorder photos
$tourPhotoModel->reorder([
    $photoId1 => 1,
    $photoId2 => 2,
    $photoId3 => 3
]);
```

## Multi-Language Support

### Language Structure

The system supports multiple languages through:

1. **Language Field**: Each tour has a `language` field (en, es)
2. **Translation Groups**: Related translations share a `translation_group` ID
3. **Unique Slugs**: Slugs are unique per language combination

### Creating Translations

```php
// Create Spanish translation of existing English tour
$englishTour = $tourModel->getById(1);
$spanishTourId = $tourModel->duplicate(1, 'es');

// Update Spanish content
$tourModel->update($spanishTourId, [
    'title' => 'Aventura Machu Picchu',
    'description' => 'Descripción en español...',
    'highlights' => ['Sitio UNESCO', 'Guía profesional', 'Grupo pequeño']
]);
```

### Getting Translations

```php
// Get all language versions of a tour
$translations = $tourModel->getTranslations($translationGroup);

// Get tour in specific language with fallback
$tour = $tourModel->getBySlug($slug, $language) ?: 
        $tourModel->getBySlug($slug, 'en');
```

## JSON Fields

Several fields store JSON data for flexibility:

### Highlights
```php
$highlights = [
    "UNESCO World Heritage Site",
    "Professional bilingual guide", 
    "Small group experience",
    "Sunrise at Machu Picchu"
];
```

### Price Inclusions/Exclusions
```php
$includes = [
    "Professional guide",
    "All entrance fees",
    "Train tickets",
    "Hotel accommodation"
];

$excludes = [
    "International flights",
    "Travel insurance", 
    "Personal expenses",
    "Tips for guide"
];
```

### Activities (in tour_details)
```php
$activities = [
    "Airport transfer",
    "City tour", 
    "Cathedral visit",
    "Qorikancha Temple"
];
```

## Usage Examples

### Creating a Complete Tour

```php
// 1. Create main tour
$tourData = [
    'name' => 'Amazon Adventure',
    'title' => 'Amazon Rainforest Expedition',
    'subtitle' => 'Explore the Heart of the Amazon',
    'language' => 'en',
    'short_description' => 'Immerse yourself in the Amazon...',
    'description' => 'Full detailed description...',
    'highlights' => ['Wildlife spotting', 'Eco-lodge stay', 'Night walks'],
    'price' => 1299.00,
    'price_includes' => ['Guide', 'Accommodation', 'Meals'],
    'price_excludes' => ['Flights', 'Insurance'],
    'duration_days' => 5,
    'category' => 'Adventure',
    'status' => 'active'
];

$tourId = $tourModel->create($tourData);

// 2. Add daily details
for ($day = 1; $day <= 5; $day++) {
    $tourDetailModel->create([
        'tour_id' => $tourId,
        'day' => $day,
        'title' => "Day $day Title",
        'description' => "Day $day description...",
        'activities' => ["Activity 1", "Activity 2"],
        'meals' => 'B,L,D',
        'accommodation' => 'Eco-lodge'
    ]);
}

// 3. Add photos
$tourPhotoModel->create([
    'tour_id' => $tourId,
    'image' => 'tours/amazon-wildlife.jpg',
    'title' => 'Amazon Wildlife',
    'type' => 'gallery',
    'is_featured' => 1
]);
```

### Displaying Tours

```php
// Get featured tours for homepage
$featuredTours = $tourModel->getFeatured('en', 6);

foreach ($featuredTours as $tour) {
    echo "<h3>{$tour['title']}</h3>";
    echo "<p>{$tour['short_description']}</p>";
    echo "<p>Price: $" . number_format($tour['price'], 2) . "</p>";
    
    // Parse JSON highlights
    $highlights = json_decode($tour['highlights'], true);
    foreach ($highlights as $highlight) {
        echo "<li>$highlight</li>";
    }
}
```

### Tour Detail Page

```php
// Get full tour with all related data
$tour = $tourModel->getFullTour($tourId);

if ($tour) {
    echo "<h1>{$tour['title']}</h1>";
    echo "<p>{$tour['description']}</p>";
    
    // Display daily itinerary
    foreach ($tour['details'] as $detail) {
        echo "<h3>Day {$detail['day']}: {$detail['title']}</h3>";
        echo "<p>{$detail['description']}</p>";
        
        $activities = json_decode($detail['activities'], true);
        foreach ($activities as $activity) {
            echo "<li>$activity</li>";
        }
    }
    
    // Display photo gallery
    foreach ($tour['gallery_photos'] as $photo) {
        echo "<img src='/storage/uploads/{$photo['image']}' alt='{$photo['alt_text']}'>";
    }
}
```

## Installation

1. **Run Installation Script**:
   ```
   http://yoursite.com/install_tours_system.php
   ```

2. **Manual Installation**:
   ```sql
   -- Execute the migration file
   SOURCE database/migrations/008_create_tours_system.sql;
   ```

3. **Create Upload Directories**:
   ```bash
   mkdir -p storage/uploads/tours
   mkdir -p storage/uploads/tours/thumbnails
   chmod 755 storage/uploads/tours
   ```

## File Structure

```
app/Models/
├── Tour.php              # Main tour model
├── TourDetail.php        # Daily itinerary model  
└── TourPhoto.php         # Photo gallery model

database/migrations/
└── 008_create_tours_system.sql  # Database schema

storage/uploads/tours/    # Tour images directory
├── thumbnails/          # Thumbnail images
└── *.jpg, *.png        # Original images

install_tours_system.php # Installation script
TOURS_SYSTEM_GUIDE.md   # This documentation
```

## Security Features

1. **SQL Injection Prevention**: All queries use prepared statements
2. **File Upload Validation**: Image type and size validation
3. **Input Sanitization**: All user inputs are sanitized
4. **Access Control**: Admin authentication required
5. **CSRF Protection**: All forms include CSRF tokens

## Performance Considerations

1. **Database Indexes**: Proper indexing on frequently queried fields
2. **Image Optimization**: Automatic thumbnail generation
3. **Caching**: Consider implementing caching for frequently accessed tours
4. **Pagination**: Built-in pagination support for large datasets

## Extending the System

### Adding New Languages

1. Add language to `getAvailableLanguages()` method
2. Create translations using the duplicate functionality
3. Update frontend language switcher

### Adding Custom Fields

1. Add columns to tours table
2. Update Tour model's allowed fields arrays
3. Modify create/update forms

### Adding New Photo Types

1. Update `getAvailableTypes()` in TourPhoto model
2. Add new type to enum in database
3. Update admin interface

## Troubleshooting

### Common Issues

1. **Upload Directory Permissions**: Ensure 755 permissions on upload directories
2. **Large Image Files**: Configure PHP upload limits if needed
3. **Memory Limits**: Increase PHP memory limit for image processing
4. **Foreign Key Constraints**: Ensure proper cascade deletion setup

### Debug Mode

Enable error reporting for development:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## API Integration

The models can be easily integrated with REST APIs:

```php
// API endpoint example
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tours = $tourModel->getAll(['language' => $_GET['lang'] ?? 'en']);
        echo json_encode($tours);
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $tourId = $tourModel->create($data);
        echo json_encode(['id' => $tourId]);
        break;
}
```

This comprehensive Tours System provides a solid foundation for managing tour content with multi-language support, detailed itineraries, and rich media galleries.
