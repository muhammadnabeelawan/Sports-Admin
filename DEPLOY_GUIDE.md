# 🚀 Professional Deployment Guide (Render + GitHub + Cloud DB)

This guide will help you move your **Admin**, **POS**, and **Website** to a live server for free.

## Phase 1: Create a Central Database (FREE)
Since your three apps need to talk to the same database, you need a single cloud DB.
1.  Go to **[Aiven.io](https://aiven.io/)** and create a free account.
2.  Choose **MySQL**.
3.  Select the **Free Plan** (DigitalOcean or AWS - doesn't matter).
4.  Once created, copy the following details:
    *   **Host Name** (e.g., `mysql-1234.aivencloud.com`)
    *   **Port** (e.g., `25060`)
    *   **User** (Usually `avnadmin`)
    *   **Password**
    *   **Database Name** (Usually `defaultdb`)

---

## Phase 2: Push your code to GitHub
I have already initialized Git for you locally. You just need to create the repositories on GitHub.
1.  Go to your GitHub and create 3 **Private** repositories:
    *   `sports-admin`
    *   `sports-pos`
    *   `sports-website`
2.  In your local terminal (for each folder), run these commands:
    ```bash
    # Replace YOUR_USERNAME with your GitHub name
    git remote add origin https://github.com/YOUR_USERNAME/sports-project-name.git
    git branch -M main
    git push -u origin main
    ```

---

## Phase 3: Deploy to Render.com
### 1. Deploy the Admin (The API)
1.  Login to **Render.com** and click **New +** > **Web Service**.
2.  Connect the `sports-admin` repository.
3.  **Runtime**: PHP
4.  **Build Command**: `composer install`
5.  **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
6.  **Environment Variables (VERY IMPORTANT)**:
    *   `APP_KEY`: (I will generate this for you below)
    *   `DB_CONNECTION`: `mysql`
    *   `DB_HOST`: (From Aiven)
    *   `DB_PORT`: (From Aiven)
    *   `DB_DATABASE`: (From Aiven)
    *   `DB_USERNAME`: (From Aiven)
    *   `DB_PASSWORD`: (From Aiven)
    *   `APP_ENV`: `production`

### 2. Deploy POS & Website
Repeat the same steps for `sports-pos` and `sports-website`, but adding one extra variable:
*   `API_URL`: **Put the link of your deployed Admin here** (e.g., `https://sports-admin.onrender.com/api`)

---

## Your App Keys (Save these!)
Use these in the Render environment variables for `APP_KEY`:
*   **Admin Key**: `base64:ypTz2Z0dT881jyoEOWjw5y63OwIFGyNqMKDjl+9TQuw=`
*   **POS Key**: `base64:twd42IJJEctAJvKqYMAIwl1hlkdhHH58jTsHfc5/ZlQ=`
*   **Website Key**: `base64:ieSwJHfVjaDHK7GXVVDhSDhmpaJe49yFRsYUycWWv3I=`

---

### Final Check:
After deploying, your Admin URL will be something like `https://sports-admin.onrender.com`. 
Make sure you update the `API_URL` in the POS and Website settings on Render!
