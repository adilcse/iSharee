# ishare
```
Add custom login and registration functionality.
Add 2 columns (mobile_no, is_admin) to users table.
Users (guest as well as logged in) can submit articles. guest submissions will be marked as unapproved and will be shown once approved by admin.
Admin will manage a list of categories and one article can be assigned to multiple categories. if there are related articles for a category, that category can't be deleted.
Admin can see all tabular listings of articles once logged in and should be able to manage all details including delete.
Protect all admin specific screens using custom middleware.
User can manage only his submissions where as admin has all kinds of permissions.
On site end, there will be a listing of articles with title, excerpt, read more link and no of comments for that article.
Both title and read more will point to article details page. details page will show title, full description, related categories. all categories will have links to category specific articles listing page.
Have slug for both category and article and it should be unique. Generate the slug upon add/edit and generate the permalink to be available as model object attribute.
Have functionality to upload image for article, manage popularity and setting to be used for slider or not.
Use pagination (no of items per page to be controlled from env) to list articles with pagination links at the bottom. Each will link to respective details page with link structure article/slug.
There will be option to submit comments to an article. If by guest user, it will be marked as unapproved. all approved comments will be shown below article details.
Admin will receive emails on new article submission and article author will receive email on comment submission.
```
## Notes:
```
Use LTS for Laravel.
Controllers should have as less code as possible.
Layout should be completely responsive using bootstrap.
Migrations should be used to alter or create tables.
Both client and server side validations should be in place.
Eloquent should be used for db management.
Use mailtrap to send emails and laravel markdown feature for mails.
Add bootstrap rich text editor to manage article details.
There should be custom coding to manage the data. However, you can use laravel packages (form builder, datatables, intervention image) for related functionality.
```
## Advanced:
```
Should have the ability to switch between site end templates.
Use twilio to validate mobile no during registration. Only validated ones will be allowed to register.
Guest users should be validated with twilio for article and comment submission.
Social login.
Integrate stripe to make payment for users to allow article submission.
```
