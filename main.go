package main

import (
	"database/sql"
	"flag"
	"fmt"
	"github.com/BurntSushi/toml"
	_ "github.com/go-sql-driver/mysql"
	_ "github.com/mattn/go-sqlite3"
	"gorm.io/driver/sqlite"
	"gorm.io/gorm"
	"log"
	"upgrade/cmd/bot"
	"upgrade/db"
	"upgrade/internal/models"
)

type Config struct {
	Env      string
	BotToken string
	Dsn      string
}
type usr struct {
	ID         int
	Name       string
	TelegramID int64
	FirstName  string
	LastName   string
}

func main() {
	db.GetMsg()
	fff := *db.MsgOutPointer
	fmt.Println(fff)

	configPath := flag.String("config", "config/local.toml", "Path to config file")
	flag.Parse()
	cfg := &Config{}
	_, err := toml.DecodeFile(*configPath, cfg)
	if err != nil {
		log.Fatalf("Ошибка чтения файла конфигурации %v", err)
	}
	db, err := gorm.Open(sqlite.Open(cfg.Dsn), &gorm.Config{})
	if err != nil {
		log.Fatalf("Ошибка подключения к базе данных %v", err)
	}
	dbs, err := sql.Open("sqlite3", "upgrade.db")
	if err != nil {
		fmt.Println("Error: ", err)
	}
	defer func(dbs *sql.DB) {
		err := dbs.Close()
		if err != nil {
			fmt.Println("Error: ", err)
		}
	}(dbs)
	rows, err := dbs.Query("SELECT * FROM users")
	if err != nil {
		panic(err)
	}
	defer func(rows *sql.Rows) {
		err := rows.Close()
		if err != nil {
			fmt.Println("Error: ", err)
		}
	}(rows)
	var users []usr

	for rows.Next() {
		u := usr{}
		err := rows.Scan(&u.ID, &u.Name, &u.TelegramID, &u.FirstName, &u.LastName)
		if err != nil {
			fmt.Println("Error: ", err)
			continue
		}
		users = append(users, u)
	}
	for _, u := range users {
		fmt.Println(u.TelegramID)
	}

	upgradeBot := bot.UpgradeBot{
		Bot:   bot.InitBot(cfg.BotToken),
		Users: &models.UserModel{Db: db},
	}

	upgradeBot.Bot.Handle("/start", upgradeBot.StartHandler)
	upgradeBot.Bot.Start()
}
