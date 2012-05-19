package br.com.cursoandroid.gastos;

import br.com.cursoandroid.gastos.dominio.Atividade;
import br.com.cursoandroid.gastos.servicos.AtividadeService;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

public class GastosActivity extends Activity {
	public static final int ADD_ATIVIDADE = 1;
	
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
        };
        
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1,android.R.id.text1,values);
       
        lista.setAdapter(adapter);
        
        lista.setOnItemClickListener( this.listClickEvent );
        
        //saldo
        String saldo = "Saldo: " + servico.getTotal();
        
        //saldo textView
        TextView saldoTextView = (TextView) findViewById(R.id.saldo);
        
        saldoTextView.setText(saldo);
        
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
    	// TODO Auto-generated method stub
    	menu.add(0, ADD_ATIVIDADE, 0, R.string.menu_add_atividade).setIcon(android.R.drawable.ic_menu_add);
    	return true;
    }
    
    @Override
    public boolean onMenuItemSelected(int featureId, MenuItem item) {
    	// TODO Auto-generated method stub
    	int itemId = item.getItemId();
    	
    	if(itemId == ADD_ATIVIDADE){
    		final CharSequence[] items = {"Receita", "Despesa"};

    		AlertDialog.Builder builder = new AlertDialog.Builder(this);
    		builder.setTitle("Escolha o tipo de atividade");
    		builder.setItems(items, new DialogInterface.OnClickListener() {
    		    public void onClick(DialogInterface dialog, int item) {
    		    	String sendValue = null;
    		    	
    		    	if(items[item].equals("Receita")){
    		    		sendValue = Atividade.RECEITA;
    		    	} else {
    		    		sendValue = Atividade.DESPESA;
    		    	}
    				
    				if(!sendValue.isEmpty()){
    					Intent intent = new Intent(self, CadastroActivity.class);
    					intent.putExtra("tipocadastro", sendValue);
    					startActivity(intent);
    				}
    		    }
    		});
    		AlertDialog alert = builder.create();
    		alert.show();
    		return true;
    	}
    	
    	return false;
    }
}