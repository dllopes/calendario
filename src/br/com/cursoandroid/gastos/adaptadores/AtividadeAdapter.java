package br.com.cursoandroid.gastos.adaptadores;

import java.util.List;

import br.com.cursoandroid.gastos.R;
import br.com.cursoandroid.gastos.dominio.Atividade;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

public class AtividadeAdapter extends BaseAdapter {
	
	private Context contexto;
	private List<Atividade> atividadeList;
	
	public AtividadeAdapter(Context contexto, List<Atividade> atividades){
		this.contexto = contexto;
		this.atividadeList = atividades;
	}

	@Override
	public int getCount() {
		return atividadeList.size();
	}

	@Override
	public Object getItem(int position) {
		return atividadeList.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		
		//recupera a atividade da posição atual
		Atividade atividade = atividadeList.get(position);
		
		//cria a instância do layout XML para os objetos correspondentes
		// na view
		LayoutInflater inflater = (LayoutInflater) contexto.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		
		View view = inflater.inflate(R.layout.list_view_personalizada, null);
		
		//
		
		return null;
	}

}
