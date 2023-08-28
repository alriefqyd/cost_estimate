function getUrlSegment(index) {
        // Get the full pathname from window.location
        var pathname = window.location.pathname;

        // Split the pathname into an array of segments
        var segments = pathname.split('/');

        // Get the segment at the specified index (adjust index if needed)
        var segment = segments[index];

        return segment;
    }
