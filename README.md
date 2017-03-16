---
services: active-directory
platforms: php
author: brandwe
---

# Calling the Azure AD Graph API from a PHP web app

This PHP sample application demonstrates how to query data from Azure Active Directory using the Graph API, a RESTful interface that allows programmatic access to tenant data such as users, contacts, groups, roles etc. This application uses OAuth 2.0 Client Credentials to authenticate and get authorization to the Graph API.

## How To Run This Sample

To run this sample you will need:
- Webmatrix http://www.microsoft.com/web/webmatrix/
- An Internet connection
- An Azure Active Directory (Azure AD) tenant. For more information on how to get an Azure AD tenant, please see [How to get an Azure AD tenant](https://azure.microsoft.com/en-us/documentation/articles/active-directory-howto-tenant/) 
- A user account in your Azure AD tenant. This sample will not work with a Microsoft account, so if you signed in to the Azure portal with a Microsoft account and have never created a user account in your directory before, you need to do that now.

### Step 1:  Clone or download this repository

From your shell or command line:

`git clone git@github.com:Azure-Samples/active-directory-php-graphapi-web.git`

### Step 2:  Run the sample from WebMatrix

The sample app is preconfigured to read data from a Demonstration company (GraphDir1.onMicrosoft.com) in Azure AD. 

### Step 3:  Running this application with your Azure Active Directory tenant

#### Register the Sample app for your own tenant

1. Sign in to the [Azure portal](https://portal.azure.com).
2. On the top bar, click on your account and under the **Directory** list, choose the Active Directory tenant where you wish to register your application.
3. Click on **More Services** in the left hand nav, and choose **Azure Active Directory**.
4. Click on **App registrations** and choose **Add**.
5. Enter a friendly name for the application, for example "SinglePageApp-DotNet" and select "Web Application and/or Web API" as the Application Type. For the sign-on URL, enter the base URL for the sample, which is by default `https://localhost:44326/`. Click on **Create** to create the application.
6. In the Settings menu, choose **Reply URLs** and add the reply URL address used to return the authorization code returned during Authorization code flow.  For example: "https://localhost:44322/".

All done!  Before moving on to the next step, you need to find the Client ID of your application, and create an App Key.

1. While still in the Azure portal, choose your application, click on **Settings** and choose **Properties**.
2. Find the Application ID value and copy it to the clipboard.
3. For the App ID URI, enter `https://<your_tenant_name>/WebApp`, replacing `<your_tenant_name>` with the domain name of your Azure AD tenant. For Example "https://contoso.com/WebApp".
4. From the Settings menu, choose **Keys** and add a key - select a key duration of either 1 year or 2 year. When you save this page, the key value will be displayed, copy and save the value in a safe location - you will need this key later to configurate the Client Credentials for this app - this key value  will not be displayed again, nor retrievable by any other means, so please record it as soon as it is visible from the Azure Portal.
5. Configure Permissions for your application - in the Settings menu, choose the "Required permissions" section, click on **Add**, then **Select an API**, and select "Microsoft Graph" (this is the Graph API). Then, click on  **Select Permissions** and select "Read Directory data". 

#### Configuring the PHP sample
1. Start Webmatrix, and select Open from the main screen, and select Folder, and navigate to the PHP folder of this project.  
2. Webmatrix will initialize the application 
3. Open the Settings.php file.  update the $appTenantDomainName to be your tenant identifier (any verified domain owned by the tenant, e.g. Contoso.onMicrosoft.com, contoso.com etc.).  update $appPrincipalId to the the Client ID recorded from previous step in the Azure portal.  Update $password with the key value configured in the previous step in the Azure Portal. Save changes.
4. Select Run, and try accessing Users, Groups and trying the differential query features.

## About The Code

Coming soon.
