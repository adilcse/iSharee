# iShare (article publishing app)
## Technology used:-
- html
- php laravel (7.0)
- css
- javascript
- bootstrap/mdbootstrap

## Description
- User can register to this app by providing basic details like email address,name mobile number and password.
- User can also login by there social account.
- Guest user can also login by clicking "guest login" button.
- Admin can login by admin email and password.

## Guest user:-
- Guest user can view 
    - all published articles in homepage.
    - articles published by different users.
    - article by catagory.
- Guest user can add article and make payment for it.
- Only articles approved by admin will be shown in artilce home page.
- Guest user can comment on any article.
- Comment will be shown after admin approval.

## Registered user:-
- Registered user can view 
    - all published articles in homepage.
    - articles published by different users.
    - article by catagory.
- Registered user can add articles and make payment for it.
- After payment articles will be published.
- He/She can like in any article.
- He/She can comment in any articles.
- He/She can change his/her like status to any article.
- He/She can deleted his/her comment.
- A user will be notified by email if anyone added comment to his/her article.
- View his/her all articles.
- Can edit or delete his/her article.
- View his/her profile.
- Can change his/her name and password in profile section.
- Can Change his/her password by entering otp sent to registered email address.

## Admin:-
- Have all registered user access.
- Can view admin dashboard.
- Admin dashboard contains
    - Articles table
        - details of all articles added by any user and its current status.
        - can change status of an article to pulished/pending.
        - Delete option for an article.
        - creator and number of views and likes for an article.
    - User table
        - details of all users and number of articles published.
        - status of users.
        - option to change status of a users.
        - Number of liked article by a user.
    - Guest comment table
        - Article details where guest user commented.
        - Comment of guest user.
        - option to approve or reject comment.
- Can edit or delete any article or remove it from publish list.
- Can delete any article.
- Can delete any comment in any articles.
- Can view any user profile.
- Can update mobile number, email address, name of any user.
- Can change verify status of phone number and email address of any user. 
- Will be notified via email for every new post added by any user.

### Required:-
- twilio account for mobile verification.
- Stripe for payment.
- Google service account for accessing gcloud bucket for image storage.
- Gcloud bucket.
- Mysql server.
- smtp mail server.