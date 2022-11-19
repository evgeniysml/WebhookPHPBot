package models

import (
	_ "github.com/go-sql-driver/mysql"
	"gorm.io/gorm"
	_ "gorm.io/gorm"
)

type User struct {
	ID         uint
	Name       string `json:"name"`
	TelegramId int64  `json:"telegram_id"`
	FirstName  string `json:"first_name"`
	LastName   string `json:"last_name"`
}

type UserModel struct {
	Db *gorm.DB
}

func (m *UserModel) Create(user User) error {
	result := m.Db.Create(&user)
	return result.Error
}

func (m *UserModel) FindOne(telegramId int64) (*User, error) {
	existUser := User{}
	result := m.Db.First(&existUser, User{TelegramId: telegramId})
	if result.Error != nil {
		return nil, result.Error
	}
	return &existUser, nil
}

//
//func CreateUser(Name string, TelegramID int64, FirstName string, LastName string) {
//
//	type MyUsers struct {
//		ID         uint
//		Name       string `json:"name"`
//		TelegramId int64  `json:"telegram_id"`
//		FirstName  string `json:"first_name"`
//		LastName   string `json:"last_name"`
//	}
//
//	db, err := sql.Open("mysql", "root:root@/send_db")
//	if err != nil {
//		fmt.Println("Error: ", err)
//	}
//	defer func(db *sql.DB) {
//		err := db.Close()
//		if err != nil {
//			fmt.Println("Error: ", err)
//		}
//	}(db)
//	val, err := db.Prepare("INSERT INTO users (name, telegram_id, first_name, last_name) VALUES (?,?,?,?)")
//	if err != nil {
//		fmt.Println("Error: ", err)
//	}
//	_, err = val.Exec(Name, TelegramID, FirstName, LastName)
//	if err != nil {
//		fmt.Println("Error: ", err)
//	}
//	defer func(db *sql.DB) {
//		err := db.Close()
//		if err != nil {
//			fmt.Println("Error: ", err)
//		}
//	}(db)
//}
