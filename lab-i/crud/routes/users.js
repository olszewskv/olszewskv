var express = require("express");
var fs = require("fs");
var path = require("path");
const { DatabaseSync } = require("node:sqlite");
var router = express.Router();

const dbPath = path.resolve(__dirname, "..", "data.db");
const db = new DatabaseSync(dbPath);

function findUser(id) {
  return db.prepare("SELECT * FROM user WHERE id = ?").get(id);
}

db.exec(fs.readFileSync(path.join(__dirname, "..", "sql", "user.sql"), "utf8"));

router.get("/", function (req, res) {
  var users = db.prepare("SELECT * FROM user ORDER BY id").all();
  res.render("users/index", { title: "User list", users: users });
});

router.get("/new", function (req, res) {
  res.render("users/form", { title: "Add user", user: {}, action: "/users" });
});

router.post("/", function (req, res) {
  db.prepare("INSERT INTO user (name, email, age) VALUES (?, ?, ?)").run(
      req.body.name,
      req.body.email,
      req.body.age
  );
  res.redirect("/users");
});

router.get("/:id", function (req, res, next) {
  var user = findUser(req.params.id);
  if (!user) {
    return next();
  }
  res.render("users/show", { title: user.name, user: user });
});

router.get("/:id/edit", function (req, res, next) {
  var user = findUser(req.params.id);
  if (!user) {
    return next();
  }
  res.render("users/edit", {
    title: "Edit user",
    user: user
  });
});

router.post("/:id/edit", function (req, res, next) {
  var user = findUser(req.params.id);
  if (!user) {
    return next();
  }
  db.prepare("UPDATE user SET name = ?, email = ?, age = ? WHERE id = ?").run(
      req.body.name,
      req.body.email,
      req.body.age,
      req.params.id
  );
  res.redirect("/users");
});

router.post("/:id/delete", function (req, res) {
  db.prepare("DELETE FROM user WHERE id = ?").run(req.params.id);
  res.redirect("/users");
});

module.exports = router;