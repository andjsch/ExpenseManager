{
  "name": "{{ config('app.name') }}",
  "display": "standalone",
  "theme_color": "#ffffff",
  "icons": [
    {
      "src": "{{ asset('/resources/favicon.png') }}",
      "sizes": "512x512",
      "type": "image/png"
    },
    {
      "src": "{{ asset('/resources/apple_touch.png') }}",
      "sizes": "180x180",
      "type": "image/png"
    }
  ]
}