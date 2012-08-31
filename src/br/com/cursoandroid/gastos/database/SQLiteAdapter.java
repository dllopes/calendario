package br.com.cursoandroid.gastos.database;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class SQLiteAdapter extends SQLiteOpenHelper {
	private static final String DATABASE_NAME = "gastos_data";
	private static final int DATABASE_VERSION = 1;

	public SQLiteAdapter(Context context){
		super(context, SQLiteAdapter.DATABASE_NAME, null, SQLiteAdapter.DATABASE_VERSION);
	}
	@Override
	public void onCreate(SQLiteDatabase database) {
		database.execSQL("CREATE TABLE gastos (id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT, data_inicio TEXT, valor REAL, tipo TEXT);");
	}

	@Override
	public void onUpgrade(SQLiteDatabase arg0, int arg1, int arg2) {
		// TODO Auto-generated method stub
		
	}

}
