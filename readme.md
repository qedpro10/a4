DWA15 A4 Assignment - Jen Smith
DayStocker is an application that analyzes stock data for a specific pattern called the
Bullish Engulfing pattern (BEP).  When this happens its usually a good time to buy ---
if you're a day trader.
The application utilizes data available from YAHOO stocks.  This data isn't
that great but it provides enough to do candlestick charts that are used to
visualize the BEP.

Graphing is done via Google Charts visualization because they support candlestick charts.

The user needs to create a account and log otherwise the only pages available to the
user are the login/register page or the About page.

About Page
This page provides an overview of what the application analyzes.  It describes
what BEP is and how its used.

Home Page
This page shows the list of stocks the user is subscribed to.  They can analyze, edit
or remove from favorites

Search page
Search page allows the user to search the Stock Exchange for new stocks to monitor.
or they can serach locally if they have a lot of stocks to sift thru.
local searches redirect back to the serach page while stock exchange searches do
to a search Results page to display the new stock data.


Search Results Page
The stock exchange search will come back with stock data and the option to add to favorites.

Editing A Stock
Once the stock has been added to the database thru a Stock Exchange search, the
some of the informtion can be updated.  
- Stock exchange
- Company logo
- Company Investor Site
Can all be updated.

Adding a Company Logo
The company logo can be added.  This info is not provided by YAHOO and needs to
be added later.
Example test would be to add the stock KO.  This is Coka-Cola Corp.  Once its added,
you will see that the logo has a stock "no image" for the logo.  You can modify the
logo on the edit page and add the link
https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg
Saving the changes will update the logo link and redirect you back to the
home page which will show the updated KO logo.

Changing the Stock Exchange
The stock exchange is set by the YAHOO data when the stock is added to the database.
However it is possible to change this information.  Note in real life, this does
occur.  An example what when Ciena (CIEN) moved from NASDAQ to NYSE in 2015.

Deleting a Stock
Delete means two different things.  If a user has added/favorited a stock, then
deleting it means it is removed from their portfolio.  If the stock is not in
any other users portfolio then the stock is deleted out of the database.

Main Tables
Stock table
Users Table
Stock Users pivot table
Exchanges table
