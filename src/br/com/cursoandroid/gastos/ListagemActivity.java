package br.com.cursoandroid.gastos;

import android.app.Activity;
import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class ListagemActivity extends Activity{
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		setContentView(R.layout.lista_atividade);
		super.onCreate(savedInstanceState);
		
		ListView lista = (ListView) findViewById(R.id.listagemPrincipal);
        
        String[] values = new String[]{
        	"Teste", "Teste2", "Teste3"	
        };
        
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1,android.R.id.text1,values);
       
        lista.setAdapter(adapter);
	}
}
