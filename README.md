# Prime Title Inc. - Professional Escrow & Title Services Website

A modern, responsive website for Prime Title Inc., offering comprehensive escrow and title services including Bond for Deed contracts, private mortgage servicing, lease purchase agreements, and more.

## Features

- **Modern, Professional Design** - Clean and trustworthy aesthetic with a professional color scheme
- **Fully Responsive** - Optimized for desktop, tablet, and mobile devices
- **Multiple Pages** - Home, About, Services, Resources, and Contact pages
- **Interactive Elements** - Smooth scrolling, animated counters, dropdown menus, and FAQ accordion
- **Contact Forms** - Built-in form validation for contact and account lookup
- **Service Sections** - Detailed information about all escrow services offered
- **Testimonials** - Client feedback section with star ratings
- **Resource Center** - FAQ section, downloadable forms, and account lookup
- **SEO Optimized** - Proper meta tags and semantic HTML structure

## Pages Overview

### Home Page (`index.html`)
- Hero section with call-to-action
- Service overview cards
- Statistics section with animated counters
- Client testimonials
- Why choose us section
- Full footer with contact information

### About Page (`about.html`)
- Company introduction
- Mission, vision, and values
- Why choose us section
- Team members showcase

### Services Page (`services.html`)
- Bond for Deed Contracts
- Private Mortgage Servicing
- Lease Purchase Agreements
- Wrap-Around Mortgages
- Installment Option Contracts

### Resources Page (`resources.html`)
- Customer account lookup
- Downloadable forms and documents
- Client testimonials with ratings
- Comprehensive FAQ section with accordion

### Contact Page (`contact.html`)
- Contact form with validation
- Business hours and contact information
- Social media links
- Map placeholder for office location

## File Structure

```
Prime/
│
├── index.html              # Home page
├── about.html              # About us page
├── services.html           # Services page
├── resources.html          # Resources page
├── contact.html            # Contact page
├── README.md              # This file
│
├── css/
│   ├── styles.css         # Main stylesheet
│   └── pages.css          # Page-specific styles
│
├── js/
│   ├── script.js          # Main JavaScript functionality
│   └── resources.js       # Resources page specific JS
│
└── images/
    ├── README.md          # Image attribution and sources
    └── sources.txt        # Detailed list of all images used
```

## Technologies Used

- **HTML5** - Semantic markup
- **CSS3** - Modern styling with CSS Grid and Flexbox
- **JavaScript (ES6+)** - Interactive features and form validation
- **Font Awesome 6.4.0** - Icons
- **Google Fonts (Inter)** - Typography
- **Unsplash Images** - Free high-quality stock photography

## Setup Instructions

1. **Clone or Download** the project files to your local machine

2. **Open in Browser**
   - Simply open `index.html` in any modern web browser
   - No build process or server required for basic functionality

3. **For Development**
   - Use a local development server for best results:
   ```bash
   # Using Python
   python -m http.server 8000
   
   # Using Node.js with http-server
   npx http-server
   
   # Using PHP
   php -S localhost:8000
   ```
   - Then navigate to `http://localhost:8000`

## Customization Guide

### Updating Contact Information

Update these files with your actual contact details:
- Phone numbers: Search for `(555) 555-1234` and replace
- Email addresses: Search for `info@primetitle-inc.com` and replace
- Physical address: Search for `123 Main Street` and replace

### Changing Colors

Edit the CSS variables in `css/styles.css` (lines 10-30):

```css
:root {
    --primary-color: #1e3a8a;      /* Main brand color */
    --primary-dark: #1e40af;       /* Darker variant */
    --primary-light: #3b82f6;      /* Lighter variant */
    --accent-color: #0891b2;       /* Accent color */
    /* ... more colors ... */
}
```

### Adding Your Logo

1. Replace the text logo in the navigation:
   - Find `<span class="logo-text">Prime Title Inc.</span>`
   - Replace with: `<img src="path/to/your/logo.png" alt="Prime Title Inc.">`

2. Add appropriate CSS styling for the logo image

### Replacing Stock Images

All images are currently loaded from Unsplash.com (free stock photos). To replace them:

1. **Option 1 - Use Different Unsplash Images:**
   - Visit [Unsplash.com](https://unsplash.com)
   - Search for your desired image
   - Copy the image URL
   - Replace the URL in the HTML file
   - Add sizing: `?w=1920&q=80` for hero images, `?w=800&q=80` for content images

2. **Option 2 - Use Your Own Images:**
   - Create an `images` folder in your project
   - Add your images to this folder
   - Replace the Unsplash URLs with relative paths: `images/your-image.jpg`

3. **Image List:**
   - Home hero: Modern house exterior
   - About page: Office building and team photos
   - Services page: Business documents
   - Contact page: Office building at dusk
   - Resources page: Business analytics

See `images/sources.txt` for complete image attribution and URLs.

### Updating Content

- **Home page stats**: Edit the `data-target` attributes in the stats section
- **Services**: Modify content in `services.html`
- **Team members**: Update or add team members in `about.html`
- **Testimonials**: Edit testimonials in both `index.html` and `resources.html`
- **FAQs**: Add or modify FAQ items in `resources.html`

### Adding Real Forms Functionality

The contact forms currently use client-side validation only. To connect to a backend:

1. **Using a Form Service** (Formspree, Netlify Forms, etc.):
   ```html
   <form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
   ```

2. **Using Your Own Backend**:
   - Modify the form submit handlers in `js/script.js`
   - Add AJAX calls to your API endpoint
   - Handle responses and errors appropriately

3. **Using PHP** (if you have a PHP server):
   - Create a `contact.php` file
   - Update form action: `<form action="contact.php" method="POST">`

### Adding Google Maps

Replace the map placeholder in `contact.html`:

```html
<div class="map-placeholder">
    <iframe 
        src="https://www.google.com/maps/embed?pb=YOUR_EMBED_CODE"
        width="100%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
</div>
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Features Breakdown

### Interactive JavaScript Features

- **Mobile Navigation Toggle** - Responsive hamburger menu
- **Smooth Scrolling** - Smooth scroll to anchor links
- **Animated Counters** - Statistics count up on scroll into view
- **Fade-in Animations** - Content fades in as you scroll
- **Form Validation** - Real-time form validation with error messages
- **FAQ Accordion** - Expandable/collapsible FAQ items
- **Dropdown Menus** - Service and resource dropdown navigation
- **Scroll to Top Button** - Appears after scrolling down

### Responsive Design

The website is fully responsive with breakpoints at:
- **992px** - Tablet landscape
- **768px** - Tablet portrait
- **576px** - Mobile devices

## Performance Optimization Tips

1. **Images**: Add actual images and optimize them (use WebP format when possible)
2. **Lazy Loading**: Images are set up for lazy loading with Intersection Observer
3. **Minification**: For production, minify CSS and JavaScript files
4. **CDN**: Consider hosting Font Awesome and Google Fonts locally for better performance
5. **Caching**: Implement browser caching via `.htaccess` or server configuration

## Deployment

### GitHub Pages
1. Push code to GitHub repository
2. Go to Settings → Pages
3. Select branch to deploy
4. Your site will be live at `https://username.github.io/repository-name/`

### Netlify
1. Connect your GitHub repository
2. Deploy settings: 
   - Build command: (none needed)
   - Publish directory: `/`
3. Deploy site

### Traditional Web Hosting
1. Upload all files via FTP/SFTP
2. Ensure `index.html` is in the root directory
3. Set appropriate file permissions

## Security Considerations

- **Form Submissions**: Implement server-side validation and sanitization
- **Contact Information**: Consider using contact forms instead of direct email links to prevent spam
- **SSL Certificate**: Always use HTTPS for production websites
- **Content Security Policy**: Implement CSP headers for additional security

## Accessibility

The website includes:
- Semantic HTML5 elements
- ARIA labels for icon-only links
- Sufficient color contrast ratios
- Keyboard navigation support
- Focus indicators for interactive elements

## Future Enhancements

Consider adding:
- [ ] Client login portal for account management
- [ ] Online payment processing integration
- [ ] Live chat support widget
- [ ] Blog section for industry news and updates
- [ ] Document upload functionality
- [ ] Email newsletter subscription
- [ ] Multi-language support
- [ ] Dark mode toggle

## Support & Customization

For additional customization or support with this website:
- Review the inline code comments
- Check the CSS custom properties for easy theming
- Modify the JavaScript for additional functionality

## License

This website template is created for Prime Title Inc. All rights reserved.

---

**Built with ❤️ for Prime Title Inc.**

*Last Updated: November 2025*

