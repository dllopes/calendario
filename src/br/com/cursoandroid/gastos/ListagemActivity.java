package br.com.cursoandroid.gastos;

import java.util.ArrayList;
import java.util.List;
import br.com.cursoandroid.gastos.dominio.Atividade;
import br.com.cursoandroid.gastos.servicos.AtividadeService;
import android.app.Activity;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

public class ListagemActivity extends Activity{
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		setContentView(R.layout.lista_atividade);
		super.onCreate(savedInstanceState);
		
		Bundle extras = getIntent().getExtras();
		String itemName = extras.getString("tipo");
		
		List<Atividade> atvs = new ArrayList<Atividade>();
		
		atvs = AtividadeService.getInstance().getAll(itemName);
		
		ListView listagem = (ListView)findViewById(R.id.listagemPrincipal);
		
		ArrayAdapter<Atividade> adapter = new ArrayAdapter<Atividade>(this, android.R.layout.simple_list_item_1, atvs);
		
		listagem.setAdapter(adapter);
		
		
		//total
		String total = (itemName.equals("R")) 
					? AtividadeService.getInstance().getTotalReceitas() 
					: AtividadeService.getInstance().getTotalDespesas() ;
		
		TextView totalview = (TextView) findViewById(R.id.total);
		
		totalview.setText("Total "+ total);
		
	}
}
