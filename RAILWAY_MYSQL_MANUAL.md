# Railway MySQL - Step-by-Step (With Screenshots)

## ⚠️ Important: यह manually Railway Dashboard में करना है

मैं code से नहीं कर सकता, लेकिन exact steps दे रहा हूँ।

---

## 📍 Step 1: Railway Dashboard खोलिए

```
Open: https://railway.app
```

---

## 📍 Step 2: अपना Project खोलिए

```
Dashboard में अपना project name देखिए:
"emp-mngt" या "Employee Management System"

Click करिए project पर
```

---

## 📍 Step 3: Left Sidebar देखिए

```
आपको यह दिखेगा:
- Services (left side)
- Your App Service (blue/green box)
```

---

## 📍 Step 4: "Add Service" Button ढूंढिए

```
देखिए कहाँ है:
- Left sidebar के नीचे
- या Project header में "+" button
- या "Add Service" text link

Click करिए
```

---

## 📍 Step 5: Services List दिखेगी

```
Available services:
- MySQL ← यह चुनना है
- PostgreSQL
- Redis
- MongoDB
- etc.

"MySQL" पर click करिए
```

---

## 📍 Step 6: MySQL Deploy होगा

```
Wait करिए 1-2 minutes

आपको दिखेगा:
- MySQL service (नया blue/green box)
- MySQL logs
```

---

## 📍 Step 7: MySQL को App से Connect करिए

```
यह step important है!

करिए:
1. MySQL Service box खोलिए
2. Right side में "Connect" button देखिए
3. Click करिए "Connect"
4. Dropdown से अपना App select करिए
5. Confirm करिए
```

---

## 📍 Step 8: Redeploy करिए

```
App automatically redeploy होगा
या आप manually कर सकते हैं:

Deployments → Redeploy button → Click करिए
```

---

## ✅ Verify करिए MySQL Connected:

### Option 1: Logs देखिए
```
App Service → Logs tab

देखिए:
✅ MySQL is ready!
📊 Running database migrations...
✅ Setup complete! Starting Apache...
```

### Option 2: Variables देखिए
```
App Service → Variables tab

ये होने चाहिए:
- DB_HOST (कुछ value होगी)
- DB_PORT (3306)
- DB_DATABASE (railway)
- DB_USERNAME (root)
- DB_PASSWORD (कुछ value होगी)
```

---

## 🎯 अगर Connect नहीं हुआ:

### Problem 1: MySQL service नहीं दिखा
```
Fix:
1. Project refresh करिए
2. MySQL service ढूंढिए
3. Status "Active" होनी चाहिए
```

### Problem 2: Connect button नहीं दिख रहा
```
Fix:
1. MySQL service पर right-click करिए
2. "Connect" option देखिए
3. या Settings → Connection
```

### Problem 3: App variables नहीं populated हुए
```
Fix: Manually add करिए
1. App Service → Variables
2. Add करिए:
   DB_HOST = mysql.railway.internal
   DB_PORT = 3306
   DB_DATABASE = railway
   DB_USERNAME = root
   DB_PASSWORD = (MySQL से copy करिए)
3. Redeploy करिए
```

---

## 🚀 After Setup Complete:

### You Can:
1. ✅ Login at `/login`
2. ✅ Use admin account: `admin@email.com` / `secret`
3. ✅ Access database via phpmyadmin (if configured)
4. ✅ All routes working

### Your Public URL:
```
https://emp-mngt-production-XXXXX.railway.app
```

Find it at:
```
Dashboard → Deployments → Open / Public URL
```

---

## 📞 If Still Stuck:

1. **Check Railway Status:** https://status.railway.app
2. **Read Docs:** https://docs.railway.app
3. **Railway Discord:** https://discord.gg/railway

---

## 🎉 Expected Timeline:

```
- MySQL add: 1-2 minutes
- Deploy: 2-3 minutes
- Migrations: 1-2 minutes
- Total: 5-10 minutes

Total time: About 10 minutes
```

---

## ✅ Final Checklist:

- [ ] Railway Dashboard खोला
- [ ] Project खोला
- [ ] MySQL service add किया
- [ ] MySQL को App connect किया
- [ ] Redeploy किया
- [ ] Logs में "MySQL is ready!" दिखा
- [ ] Variables में DB_HOST populate हुआ
- [ ] Public URL accessible है
- [ ] Login page काम कर रहा है

---

**That's it! MySQL will be added manually through Railway Dashboard.** 🚀

Once you complete these steps, your app will have:
- ✅ Database connected
- ✅ Migrations running
- ✅ Test data seeded
- ✅ Everything working

**Share me the logs once MySQL is connected!** 📸
