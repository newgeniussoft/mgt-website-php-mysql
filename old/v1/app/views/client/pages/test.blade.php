@import('app/utils/helpers/helper.php')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madagascar Green Tours</title>
</head>
<body>
    <h1>{{ $language == "es" ? "Prueba todos los modelos aquí" : "Test all model here" }}</h1>

    <h3>Tours</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($tours[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($tours as $tour)
            <tr>
                <?php foreach(getKeys($tour) as $key): ?>
                    <td>{{ $tour->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>
    <h3>Pages</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($pages[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($pages as $page)
            <tr>
                <?php foreach(getKeys($page) as $key): ?>
                    <td>{{ $page->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>

    <h3>Reviews</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($reviews[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($reviews as $review)
            <tr>
                <?php foreach(getKeys($review) as $key): ?>
                    <td>{{ $review->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>

    <h3>Galleries</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($galleries[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($galleries as $gallery)
            <tr>
                <?php foreach(getKeys($gallery) as $key): ?>
                    <td>{{ $gallery->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>
    <h3>Social Medias</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($socialMedias[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($socialMedias as $socialMedia)
            <tr>
                <?php foreach(getKeys($socialMedia) as $key): ?>
                    <td>{{ $socialMedia->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>
    <h3>Slides</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($slides[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($slides as $slide)
            <tr>
                <?php foreach(getKeys($slide) as $key): ?>
                    <td>{{ $slide->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>

    <h3>Services</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($services[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($services as $service)
            <tr>
                <?php foreach(getKeys($service) as $key): ?>
                    <td>{{ $service->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>
    <h3>Videos</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($videos[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($videos as $video)
            <tr>
                <?php foreach(getKeys($video) as $key): ?>
                    <td>{{ $video->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>
    <h3>Testimonials</h3>
    <table border="1">
        <tr>
            <?php foreach(getKeys($reviews[0]) as $key): ?>
            <th>{{ $key }}</th>
            <?php endforeach; ?>
        </tr>
        @foreach($reviews as $testimonial)
            <tr>
                <?php foreach(getKeys($testimonial) as $key): ?>
                    <td>{{ $testimonial->$key }}</td>
                <?php endforeach; ?>
            </tr>
        @endforeach
    </table>

</body>
</html>