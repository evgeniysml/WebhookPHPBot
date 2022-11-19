package db

import (
	"database/sql"
	"fmt"
	_ "github.com/go-sql-driver/mysql"
)

var MsgOut string
var MsgOutPointer = &MsgOut

func GetMsg() {
	type SendMsg struct {
		ID      int
		Message string
	}
	db, err := sql.Open("mysql", "root:root@/send_db")
	if err != nil {
		fmt.Println("Error: ", err)
	}
	defer func(db *sql.DB) {
		err := db.Close()
		if err != nil {
			fmt.Println("Error: ", err)
		}
	}(db)
	rows, err := db.Query("SELECT text FROM messages ORDER BY id DESC LIMIT 1")
	if err != nil {
		fmt.Println("Error: ", err)
	}
	defer func(rows *sql.Rows) {
		err := rows.Close()
		if err != nil {
			fmt.Println("Error: ", err)
		}
	}(rows)
	var msg []SendMsg

	for rows.Next() {
		p := SendMsg{}
		err := rows.Scan(&p.Message)
		if err != nil {
			fmt.Println(err)
			continue
		}
		msg = append(msg, p)
	}
	for _, p := range msg {
		msgOut := p.Message
		*MsgOutPointer = msgOut
	}
}
