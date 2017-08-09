import java.io.File;
import java.io.IOException;

import org.bitcoinj.core.BlockChain;
import org.bitcoinj.core.ECKey;
import org.bitcoinj.core.NetworkParameters;
import org.bitcoinj.core.PeerGroup;
import org.bitcoinj.net.discovery.DnsDiscovery;
import org.bitcoinj.params.TestNet3Params;
import org.bitcoinj.store.BlockStore;
import org.bitcoinj.store.BlockStoreException;
import org.bitcoinj.store.MemoryBlockStore;
import org.bitcoinj.store.SPVBlockStore;
import org.bitcoinj.wallet.UnreadableWalletException;
import org.bitcoinj.wallet.Wallet;
import org.junit.Test;

public class Savetest{

	private NetworkParameters params = TestNet3Params.get();
	private Wallet wallet = null;
	private File walletFile = new File("coins.dat");

	@SuppressWarnings("deprecation")
	@Test
	public void loadWallet() {
		try {
			System.out.println("here1 start?");
			wallet = Wallet.loadFromFile(walletFile);
		} catch (UnreadableWalletException e) {
			wallet = new Wallet(params);

			wallet.addKey(new ECKey());

			saveWallet();
		}

		System.out.println(wallet);
	}

	public void saveWallet() {
		try {
			wallet.saveToFile(walletFile);
		} catch (IOException e) {
			System.out.println("failed to save wallet file");
		}
	}

	public void downloadChain(BlockChain chain) {
		System.out.println("BlockChain down?");
		PeerGroup peerGroup = new PeerGroup(params, chain);
		System.out.println(params);
		peerGroup.setUserAgent("test app", "0.1");
		peerGroup.addPeerDiscovery(new DnsDiscovery(params));
		peerGroup.addWallet(wallet);
		peerGroup.startAsync();
		peerGroup.downloadBlockChain();
	}

	public void fetchMemoryBlock() {

		BlockChain chain = null;

		loadWallet();

		try {
			BlockStore blockStore = new MemoryBlockStore(params);
			chain = new BlockChain(params, wallet, blockStore);
		} catch (BlockStoreException e) {
			System.out.println("failed to initialize block store");
		}

		downloadChain(chain);
		System.out.println("here?");
		System.out.println(wallet);

		saveWallet();
	}

	@Test
	public void fetchSPVBlock() {

		File storeFile = new File("block.store");
		BlockChain chain = null;

		loadWallet();

		try {
			BlockStore blockStore = new SPVBlockStore(params, storeFile);
			chain = new BlockChain(params, wallet, blockStore);
		} catch (BlockStoreException e) {
			System.out.println("failed to initialize block store");
		}

		downloadChain(chain);
		System.out.println(wallet);

		saveWallet();
	}
}
