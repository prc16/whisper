import mariadb
import os
import time

def process_expired_posts():
    # Connect to MariaDB database
    try:
        conn = mariadb.connect(
            user="root",
            password="",
            host="localhost",
            port=3306,
            unix_socket='/opt/lampp/var/mysql/mysql.sock',
            database="whisper_db"
        )
        cursor = conn.cursor()

    except mariadb.Error as e:
        print(f"Error connecting to MariaDB: {e}")
        exit(1)

    # Query for expired posts
    query = ("SELECT id, media_file_id, media_file_ext FROM posts WHERE expire_at <= NOW()")
    print("Executing query:", query)  # Debug: Print the query before execution
    cursor.execute(query)

    # Fetch all rows
    rows = cursor.fetchall()

    # Check if any rows are returned
    if len(rows) == 0:
        return

    # Delete expired posts and associated media files
    for (post_id, media_file_id, media_file_ext) in rows:
        # Delete post from database
        delete_post_query = ("DELETE FROM posts WHERE id = ?")
        cursor.execute(delete_post_query, (post_id,))

        # Delete media file from file system
        media_file_path = f"/opt/lampp/htdocs/whisper/uploads/post_media/{media_file_id}.{media_file_ext}"
        if os.path.exists(media_file_path):
            os.remove(media_file_path)
        else:
            print(f"Media file {media_file_id}.{media_file_ext} does not exist.")
        
        print(f"1 Post deleted.")

    # Commit changes and close connections
    conn.commit()
    cursor.close()
    conn.close()

# Run the script every 10 seconds
while True:
    process_expired_posts()
    time.sleep(10)  # Sleep for 10 seconds before running the script again
