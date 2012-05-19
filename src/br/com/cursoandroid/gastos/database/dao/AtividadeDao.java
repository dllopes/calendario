package br.com.cursoandroid.gastos.database.dao;

import java.util.ArrayList;
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import br.com.cursoandroid.gastos.database.Connection;
import br.com.cursoandroid.gastos.dominio.Atividade;

public class AtividadeDao implements IAtividadeDao {
	private Context context;
	
	@Override
	public void setContext(Context context){
		this.context = context;
	}

	@Override
	public Atividade insert(Atividade obj) {
		// TODO Auto-generated method stub
		ContentValues values = new ContentValues();
		
		values.put("nome", obj.getNome());
		values.put("data_inicio", obj.getDataInicio().toString());
		values.put("valor", obj.getValor());
		values.put("tipo", obj.getTipo());
		
		Connection.getConnection(this.context).insert("gastos", null, values);
		
		//pega o ID
		String query = "SELECT id from gastos order by id DESC limit 1";
		Cursor c = Connection.getConnection(this.context).rawQuery(query, null);
		if (c != null && c.moveToFirst()) {
		    obj.setId((int)c.getLong(0)); //The 0 is the column index, we only have 1 column, so the index is 0
		}
		
		return obj;
	}

	@Override
	public Atividade update(Atividade obj) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public Atividade delete(Atividade obj) {
		// TODO Auto-generated method stub
		
		return null;
	}

	@Override
	public Atividade get(Integer id) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public ArrayList<Atividade> getAll() {
		
		Cursor c = Connection.getConnection(this.context).query("gastos", new String[]{"id","nome", "data_inicio","valor", "tipo"}, null, null, null, null, "id DESC");
		
		if(c != null){
			
			ArrayList<Atividade> lista = new ArrayList<Atividade>();
			
			Atividade atv = new Atividade();
			
			while(c.moveToNext()){
				atv.setNome(c.getString(c.getColumnIndex("nome")));
				atv.setId(c.getInt(c.getColumnIndex("id")));
				atv.setTipo(c.getString(c.getColumnIndex("tipo")));
				atv.setValor(c.getFloat(c.getColumnIndex("valor")));
				
				lista.add(atv);
			}
			
			return lista;
		}
		
		return null;
	}

	@Override
	public ArrayList<Atividade> getAllDespesas() {
		return this.getAll();
	}

	@Override
	public ArrayList<Atividade> getAllReceitas() {
		return this.getAll();
	}
	
}
