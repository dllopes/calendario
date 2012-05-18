package br.com.cursoandroid.gastos;

import br.com.cursoandroid.gastos.dominio.Atividade;
import br.com.cursoandroid.gastos.servicos.AtividadeService;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class GastosActivity extends Activity {
	public Context self = this;
	
	private AdapterView.OnItemClickListener listClickEvent = new AdapterView.OnItemClickListener() {

		@Override
		public void onItemClick(AdapterView<?> parent, View view, int position,
				long itemid) {
			
			String value = (String) parent.getAdapter().getItem(position);
			String sendValue = null;
			
			if(value.matches("Receita.+"))
				sendValue = Atividade.RECEITA;
			else if(value.matches("Despesa.+"))
				sendValue = Atividade.DESPESA;
			
			if(sendValue != null){
				Intent intent = new Intent(self, ListagemActivity.class);
				intent.putExtra("tipo", sendValue);
				startActivity(intent);
			}
		}
    	
	};
	
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
        ListView lista = (ListView) findViewById(R.id.listagemPrincipal);
        AtividadeService servico = AtividadeService.getInstance(); 
        String[] values = new String[]{
        	"Receitas " +  servico.getTotalReceitas()
        	, "Despesas " + servico.getTotalDespesas() 
        	, "Saldo " + servico.getTotal()
        };
        
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1,android.R.id.text1,values);
       
        lista.setAdapter(adapter);
        
        lista.setOnItemClickListener( this.listClickEvent );
        
    }
}