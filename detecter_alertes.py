import pymysql
import json
from datetime import datetime

# ⚙️ Configuration base de données
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "projetweb",
    "charset": "utf8mb4"
}

try:
    conn = pymysql.connect(**db_config)
    cursor = conn.cursor()

    alertes = []

    # 🕐 Règle 1 : Trop de connexions dans la dernière minute
    cursor.execute("""
        SELECT user_id, COUNT(*) as total
        FROM connexions
        WHERE date_connexion >= NOW() - INTERVAL 1 MINUTE
        GROUP BY user_id
        HAVING total > 5
    """)
    for row in cursor.fetchall():
        alertes.append({
            "user_id": row[0],
            "type": "trop_de_connexions",
            "timestamp": datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        })
        # Insertion dans la base
        cursor.execute("""
            INSERT INTO alertes (user_id, type_alerte)
            VALUES (%s, %s)
        """, (row[0], "trop_de_connexions"))

    # 🌍 Règle 2 : Plus de 3 IP différentes dans la dernière heure
    cursor.execute("""
        SELECT user_id, COUNT(DISTINCT ip) as nb_ip
        FROM connexions
        WHERE date_connexion >= NOW() - INTERVAL 1 HOUR
        GROUP BY user_id
        HAVING nb_ip > 3
    """)
    for row in cursor.fetchall():
        alertes.append({
            "user_id": row[0],
            "type": "multi_ip",
            "timestamp": datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        })
        # Insertion dans la base
        cursor.execute("""
            INSERT INTO alertes (user_id, type_alerte)
            VALUES (%s, %s)
        """, (row[0], "multi_ip"))

    # 💾 Enregistrer aussi dans alertes.json
    with open("alertes.json", "w") as f:
        json.dump(alertes, f, indent=4)

    conn.commit()
    print(f"{len(alertes)} alerte(s) détectée(s) et enregistrée(s).")

    cursor.close()
    conn.close()

except Exception as e:
    print("Erreur lors de la détection :", e)
