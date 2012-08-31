package br.com.cursoandroid.gastos.database;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

public class Connection {
	
	private static SQLiteDatabase db;
	private static SQLiteAdapter dbHelper;
	
	private Connection(){}
	
	public static SQLiteDatabase getConnection(Context context){
		if(Connection.db == null || !Connection.db.isOpen()){
			Connection.dbHelper = new SQLiteAdapter(context);
			Connection.db = Connection.dbHelper.getWritableDatabase();
		}
		
		return Connection.db;
	}
	
	public static void close(){
		if(Connection.dbHelper != null)
			Connection.dbHelper.close();
			
		if(Connection.db != null)
			Connection.db.close();
	}

}
